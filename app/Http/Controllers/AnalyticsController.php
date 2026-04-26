<?php
namespace App\Http\Controllers;
use App\Models\PageView;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class AnalyticsController extends Controller
{
    public function index()
    {
        [$selectedMonth, $selectedYear] = $this->getSelectedMonthAndYear();
        $selectedDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
        $currentMonthStart = $selectedDate->copy()->startOfMonth();
        $currentMonthEnd = $selectedDate->copy()->endOfMonth();
        $previousMonthDate = $selectedDate->copy()->subMonth();
        $previousMonthStart = $previousMonthDate->copy()->startOfMonth();
        $previousMonthEnd = $previousMonthDate->copy()->endOfMonth();
        $twoMonthsAgoDate = $previousMonthDate->copy()->subMonth();
        $twoMonthsAgoStart = $twoMonthsAgoDate->copy()->startOfMonth();
        $twoMonthsAgoEnd = $twoMonthsAgoDate->copy()->endOfMonth();

        // ==================== إحصائيات الزوار ====================
        // إجمالي المشاهدات
        $totalPageViews = PageView::whereBetween('viewed_at', [$currentMonthStart, $currentMonthEnd])->count();
        $lastMonthPageViews = PageView::whereBetween('viewed_at', [$previousMonthStart, $previousMonthEnd])->count();
        $twoMonthsAgoPageViews = PageView::whereBetween('viewed_at', [$twoMonthsAgoStart, $twoMonthsAgoEnd])->count();
        $pageViewsChange = $this->calculatePercentageChange($lastMonthPageViews, $twoMonthsAgoPageViews);
        // إجمالي الزوار الفريدين
        $uniqueVisitors = PageView::whereBetween('viewed_at', [$currentMonthStart, $currentMonthEnd])
            ->distinct('ip_address')->count('ip_address');
        $lastMonthVisitors = PageView::whereBetween('viewed_at', [$previousMonthStart, $previousMonthEnd])
            ->distinct('ip_address')->count('ip_address');
        $twoMonthsAgoVisitors = PageView::whereBetween('viewed_at', [$twoMonthsAgoStart, $twoMonthsAgoEnd])
            ->distinct('ip_address')->count('ip_address');
        $visitorsChange = $this->calculatePercentageChange($lastMonthVisitors, $twoMonthsAgoVisitors);

        // متوسط وقت الجلسة
        $avgSessionTime = PageView::whereBetween('viewed_at', [$currentMonthStart, $currentMonthEnd])->avg('time_spent') ?? 0;
        $avgSessionTimeFormatted = gmdate('i\m s\s', $avgSessionTime);

        // معدل الارتداد (Bounce Rate)
        $bounceRate = $this->calculateBounceRate($currentMonthStart, $currentMonthEnd);

        // ==================== أكثر الصفحات زيارة ====================
        $topPages = PageView::select(
                'page_url',
                'page_title',
                DB::raw('COUNT(*) as views'),
                DB::raw('AVG(time_spent) as avg_time')
            )
            ->whereBetween('viewed_at', [$currentMonthStart, $currentMonthEnd])
            ->groupBy('page_url', 'page_title')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        // ==================== الزيارات حسب الساعة ====================
        $viewsByHour = PageView::select(
                DB::raw('HOUR(viewed_at) as hour'),
                DB::raw('COUNT(*) as visitors'),
                DB::raw('COUNT(DISTINCT ip_address) as unique_visitors')
            )
            ->whereBetween('viewed_at', [$currentMonthStart, $currentMonthEnd])
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // تنسيق البيانات للرسم البياني
        $hourlyData = [];
        for ($i = 0; $i < 24; $i++) {
            $found = $viewsByHour->firstWhere('hour', $i);
            $hourlyData[] = [
                'hour' => str_pad($i, 2, '0', STR_PAD_LEFT) . ':00',
                'visitors' => $found ? $found->visitors : 0,
                'unique_visitors' => $found ? $found->unique_visitors : 0
            ];
        }
        // ==================== توزيع الأجهزة ====================
        $deviceStats = PageView::select('device_type', DB::raw('COUNT(*) as count'))
            ->whereBetween('viewed_at', [$currentMonthStart, $currentMonthEnd])
            ->groupBy('device_type')
            ->get();
        $totalDevices = $deviceStats->sum('count');
        $deviceData = [];
        foreach ($deviceStats as $device) {
            $deviceData[] = [
                'device' => ucfirst($device->device_type ?? 'unknown'),
                'count' => $device->count,
                'percentage' => $totalDevices > 0 ? round(($device->count / $totalDevices) * 100, 1) : 0
            ];
        }
        // ==================== مصادر الزيارات ====================
        $trafficSources = PageView::select('referrer', DB::raw('COUNT(*) as count'))
            ->whereBetween('viewed_at', [$currentMonthStart, $currentMonthEnd])
            ->whereNotNull('referrer')
            ->where('referrer', '!=', '')
            ->groupBy('referrer')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
        $totalReferrers = PageView::whereBetween('viewed_at', [$currentMonthStart, $currentMonthEnd])
            ->whereNotNull('referrer')->where('referrer', '!=', '')->count();
        $directTraffic = PageView::whereBetween('viewed_at', [$currentMonthStart, $currentMonthEnd])
            ->where(function ($query) {
                $query->whereNull('referrer')->orWhere('referrer', '');
            })
            ->count();
        $trafficData = [
            'direct' => [
                'name' => 'Direct',
                'count' => $directTraffic,
                'percentage' => $totalPageViews > 0 ? round(($directTraffic / $totalPageViews) * 100, 1) : 0
            ],
            'referrers' => []
        ];
        foreach ($trafficSources as $source) {
            $domain = parse_url($source->referrer, PHP_URL_HOST) ?? $source->referrer;
            $trafficData['referrers'][] = [
                'name' => $domain,
                'count' => $source->count,
                'percentage' => $totalPageViews > 0 ? round(($source->count / $totalPageViews) * 100, 1) : 0
            ];
        }
        // ==================== أكثر المنتجات مشاهدة ====================
        $topProducts = PageView::select('page_url', DB::raw('COUNT(*) as views'))
            ->whereBetween('viewed_at', [$currentMonthStart, $currentMonthEnd])
            ->where('page_url', 'LIKE', '%/product/%')
            ->groupBy('page_url')
            ->orderByDesc('views')
            ->limit(5)
            ->get();
        // ==================== نشاط المستخدمين ====================
        $activeUsers = User::whereHas('visits', function($query) use ($currentMonthStart, $currentMonthEnd) {
                $query->whereBetween('visited_at', [$currentMonthStart, $currentMonthEnd]);
            })
            ->count();
        return view('admin_panal.analytics', compact(
            'totalPageViews',
            'pageViewsChange',
            'uniqueVisitors',
            'visitorsChange',
            'avgSessionTime',
            'avgSessionTimeFormatted',
            'bounceRate',
            'topPages',
            'hourlyData',
            'deviceData',
            'trafficData',
            'topProducts',
            'activeUsers'
        ));
    }

    private function calculateBounceRate($startDate, $endDate)
    {
        // الزوار الذين شاهدوا صفحة واحدة فقط
        $singlePageVisitors = PageView::select('ip_address')
            ->whereBetween('viewed_at', [$startDate, $endDate])
            ->groupBy('ip_address')
            ->havingRaw('COUNT(*) = 1')
            ->count();

        $totalVisitors = PageView::whereBetween('viewed_at', [$startDate, $endDate])
            ->distinct('ip_address')
            ->count('ip_address');

        return $totalVisitors > 0 ? round(($singlePageVisitors / $totalVisitors) * 100, 2) : 0;
    }

    private function getSelectedMonthAndYear()
    {
        $month = session('selected_month', now()->month);
        $year = session('selected_year', now()->year);

        if (!is_numeric($month) || $month < 1 || $month > 12) {
            $month = now()->month;
        }

        if (!is_numeric($year) || $year < 2000 || $year > 2100) {
            $year = now()->year;
        }

        return [$month, $year];
    }

    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 2);
    }
}
