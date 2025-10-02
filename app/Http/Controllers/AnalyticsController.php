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
        // ==================== إحصائيات الزوار ====================
        // إجمالي المشاهدات
        $totalPageViews = PageView::count();
        $lastMonthPageViews = PageView::where('viewed_at', '>=', Carbon::now()->subMonth())->count();
        $twoMonthsAgoPageViews = PageView::whereBetween('viewed_at', [Carbon::now()->subMonths(2),Carbon::now()->subMonth()])->count();
        $pageViewsChange = $this->calculatePercentageChange($lastMonthPageViews, $twoMonthsAgoPageViews);
        // إجمالي الزوار الفريدين
        $uniqueVisitors = PageView::distinct('ip_address')->count('ip_address');
        $lastMonthVisitors = PageView::where('viewed_at', '>=', Carbon::now()->subMonth())
            ->distinct('ip_address')->count('ip_address');
        $twoMonthsAgoVisitors = PageView::whereBetween('viewed_at', [
            Carbon::now()->subMonths(2),
            Carbon::now()->subMonth()
        ])->distinct('ip_address')->count('ip_address');
        $visitorsChange = $this->calculatePercentageChange($lastMonthVisitors, $twoMonthsAgoVisitors);

        // متوسط وقت الجلسة
        $avgSessionTime = PageView::avg('time_spent') ?? 0;
        $avgSessionTimeFormatted = gmdate('i\m s\s', $avgSessionTime);

        // معدل الارتداد (Bounce Rate)
        $bounceRate = $this->calculateBounceRate();

        // ==================== أكثر الصفحات زيارة ====================
        $topPages = PageView::select(
                'page_url',
                'page_title',
                DB::raw('COUNT(*) as views'),
                DB::raw('AVG(time_spent) as avg_time')
            )
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
            ->where('viewed_at', '>=', Carbon::now()->subDay())
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
            ->whereNotNull('referrer')
            ->where('referrer', '!=', '')
            ->groupBy('referrer')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
        $totalReferrers = PageView::whereNotNull('referrer')->where('referrer', '!=', '')->count();
        $directTraffic = PageView::whereNull('referrer')->orWhere('referrer', '')->count();
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
            ->where('page_url', 'LIKE', '%/product/%')
            ->groupBy('page_url')
            ->orderByDesc('views')
            ->limit(5)
            ->get();
        // ==================== نشاط المستخدمين ====================
        $activeUsers = User::whereHas('visits', function($query) {
                $query->where('visited_at', '>=', Carbon::now()->subDays(7));
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

    private function calculateBounceRate()
    {
        // الزوار الذين شاهدوا صفحة واحدة فقط
        $singlePageVisitors = PageView::select('ip_address')
            ->where('viewed_at', '>=', Carbon::now()->subMonth())
            ->groupBy('ip_address')
            ->havingRaw('COUNT(*) = 1')
            ->count();

        $totalVisitors = PageView::where('viewed_at', '>=', Carbon::now()->subMonth())
            ->distinct('ip_address')
            ->count('ip_address');

        return $totalVisitors > 0 ? round(($singlePageVisitors / $totalVisitors) * 100, 2) : 0;
    }

    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 2);
    }
}
