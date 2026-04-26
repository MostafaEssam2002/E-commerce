<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\categories;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index()
    {
        // ========== الإحصائيات الأساسية ==========
        $stats = $this->getBasicStats();

        // ========== مقارنة الشهر الحالي بالسابق ==========
        $comparisons = $this->getMonthlyComparisons();

        // ========== المبيعات اليومية (آخر 30 يوم) ==========
        $dailySales = $this->getDailySales();

        // ========== أفضل المنتجات ==========
        $topProducts = $this->getTopProducts();

        // ========== المبيعات حسب الفئة ==========
        $categoryData = $this->getCategorySales();

        // ========== الطلبات الأخيرة ==========
        $recentOrders = $this->getRecentOrders();

        // ========== أفضل العملاء ==========
        $topCustomers = $this->getTopCustomers();

        // ========== الأداء الشهري (آخر 6 شهور) ==========
        $monthlyPerformance = $this->getMonthlyPerformance();

        return view('admin_panal.sales', $this->buildReportData($stats, $comparisons, $dailySales, $topProducts, $categoryData, $recentOrders, $topCustomers, $monthlyPerformance));
    }

    public function reports()
    {
        $stats = $this->getBasicStats();
        $comparisons = $this->getMonthlyComparisons();
        $dailySales = $this->getDailySales();
        $topProducts = $this->getTopProducts();
        $categoryData = $this->getCategorySales();
        $recentOrders = $this->getRecentOrders();
        $topCustomers = $this->getTopCustomers();
        $monthlyPerformance = $this->getMonthlyPerformance();

        return view('admin_panal.reports', $this->buildReportData($stats, $comparisons, $dailySales, $topProducts, $categoryData, $recentOrders, $topCustomers, $monthlyPerformance));
    }

    private function buildReportData($stats, $comparisons, $dailySales, $topProducts, $categoryData, $recentOrders, $topCustomers, $monthlyPerformance)
    {
        return array_merge(
            $stats,
            $comparisons,
            compact(
                'dailySales',
                'topProducts',
                'categoryData',
                'recentOrders',
                'topCustomers',
                'monthlyPerformance'
            )
        );
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

        return compact('month', 'year');
    }

    /**
     * الإحصائيات الأساسية
     */
    private function getBasicStats()
    {
        $selected = $this->getSelectedMonthAndYear();

        $totalSales = OrderDetail::whereHas('order', function ($q) use ($selected) {
            $q->whereMonth('created_at', $selected['month'])
              ->whereYear('created_at', $selected['year']);
        })->sum(DB::raw('price * quantity'));

        $completedOrders = Order::where('status', 'completed')
            ->whereMonth('created_at', $selected['month'])
            ->whereYear('created_at', $selected['year'])
            ->count();

        $pendingOrders = Order::where('status', 'pending')
            ->whereMonth('created_at', $selected['month'])
            ->whereYear('created_at', $selected['year'])
            ->count();

        $avgOrderValue = $completedOrders > 0 ? $totalSales / $completedOrders : 0;

        return compact('totalSales', 'completedOrders', 'pendingOrders', 'avgOrderValue');
    }

    /**
     * مقارنة الشهر الحالي بالشهر الماضي
     */
    private function getMonthlyComparisons()
    {
        $selected = $this->getSelectedMonthAndYear();
        $currentMonth = $selected['month'];
        $currentYear = $selected['year'];

        $selectedDate = Carbon::createFromDate($currentYear, $currentMonth, 1);
        $lastDate = $selectedDate->copy()->subMonth();
        $lastMonth = $lastDate->month;
        $lastMonthYear = $lastDate->year;

        // مبيعات الشهر الحالي
        $currentMonthSales = OrderDetail::whereHas('order', function($q) use ($currentMonth, $currentYear) {
            $q->whereMonth('created_at', $currentMonth)
              ->whereYear('created_at', $currentYear);
        })->sum(DB::raw('price * quantity'));

        // مبيعات الشهر الماضي
        $lastMonthSales = OrderDetail::whereHas('order', function($q) use ($lastMonth, $lastMonthYear) {
            $q->whereMonth('created_at', $lastMonth)
              ->whereYear('created_at', $lastMonthYear);
        })->sum(DB::raw('price * quantity'));

        $salesChange = $lastMonthSales > 0
            ? (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100
            : 0;

        // طلبات الشهر الحالي والماضي
        $currentMonthOrders = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $lastMonthOrders = Order::whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastMonthYear)
            ->count();

        $ordersChange = $lastMonthOrders > 0
            ? (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100
            : 0;

        return compact('salesChange', 'ordersChange');
    }

    /**
     * المبيعات اليومية في الشهر المحدد
     */
    private function getDailySales()
    {
        $selected = $this->getSelectedMonthAndYear();
        $start = Carbon::createFromDate($selected['year'], $selected['month'], 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        return OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw('DATE(orders.created_at) as date, SUM(order_details.price * order_details.quantity) as amount')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function($item) {
                return [
                    'date' => date('M d', strtotime($item->date)),
                    'amount' => (float) $item->amount
                ];
            });
    }

    /**
     * أفضل 5 منتجات من حيث الإيرادات
     */
    private function getTopProducts()
    {
        $selected = $this->getSelectedMonthAndYear();
        return Product::select('products.id', 'products.name', 'products.image_path')
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereMonth('orders.created_at', $selected['month'])
            ->whereYear('orders.created_at', $selected['year'])
            ->selectRaw('SUM(order_details.price * order_details.quantity) as revenue')
            ->selectRaw('SUM(order_details.quantity) as total_sold')
            ->groupBy('products.id', 'products.name', 'products.image_path')
            ->orderBy('revenue', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * المبيعات حسب الفئة
     */
    private function getCategorySales()
    {
        $selected = $this->getSelectedMonthAndYear();
        return categories::select('categories.id', 'categories.name', 'categories.image_path')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereMonth('orders.created_at', $selected['month'])
            ->whereYear('orders.created_at', $selected['year'])
            ->selectRaw('SUM(order_details.price * order_details.quantity) as value')
            ->selectRaw('COUNT(DISTINCT order_details.order_id) as orders_count')
            ->groupBy('categories.id', 'categories.name', 'categories.image_path')
            ->orderBy('value', 'desc')
            ->get();
    }

    /**
     * آخر 10 طلبات مع إمكانية الترتيب
     */
    private function getRecentOrders()
    {
        $sortBy = request()->get('sort_by', 'created_at');
        $sortDir = request()->get('sort_dir', 'desc');
        $selected = $this->getSelectedMonthAndYear();

        // التأكد من أن الترتيب آمن
        $allowedSorts = ['id', 'created_at', 'status'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'desc';

        $orders = Order::with(['user', 'orderDetails.product'])
            ->whereMonth('created_at', $selected['month'])
            ->whereYear('created_at', $selected['year'])
            ->orderBy($sortBy, $sortDir)
            ->limit(10)
            ->get()
            ->map(function($order) {
                // حساب إجمالي الطلب
                $order->total_amount = $order->orderDetails->sum(function($detail) {
                    return $detail->price * $detail->quantity;
                });

                // حساب تكلفة الشحن
                $order->shipping_cost = $order->orderDetails->sum(function($detail) {
                    return $detail->product->shipping ?? 0;
                });

                return $order;
            });

        // ترتيب حسب الاسم أو المبلغ (بعد جلب البيانات)
        if ($sortBy == 'customer') {
            $orders = $sortDir == 'asc'
                ? $orders->sortBy('user.name')
                : $orders->sortByDesc('user.name');
        } elseif ($sortBy == 'amount') {
            $orders = $sortDir == 'asc'
                ? $orders->sortBy('total_amount')
                : $orders->sortByDesc('total_amount');
        }

        return $orders;
    }

    /**
     * أفضل 5 عملاء
     */
    private function getTopCustomers()
    {
        $selected = $this->getSelectedMonthAndYear();
        return User::select('users.id', 'users.name', 'users.email', 'users.avatar')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->whereMonth('orders.created_at', $selected['month'])
            ->whereYear('orders.created_at', $selected['year'])
            ->selectRaw('COUNT(DISTINCT orders.id) as orders_count')
            ->selectRaw('SUM(order_details.price * order_details.quantity) as total_amount')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.avatar')
            ->orderBy('total_amount', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * الأداء الشهري (آخر 6 شهور)
     */
    private function getMonthlyPerformance()
    {
        $performance = [];
        $selected = $this->getSelectedMonthAndYear();
        $baseDate = Carbon::createFromDate($selected['year'], $selected['month'], 1);

        for ($i = 5; $i >= 0; $i--) {
            $month = $baseDate->copy()->subMonths($i);

            $sales = OrderDetail::whereHas('order', function($q) use ($month) {
                $q->whereYear('created_at', $month->year)
                  ->whereMonth('created_at', $month->month);
            })->sum(DB::raw('price * quantity'));

            $ordersCount = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $performance[] = [
                'month' => $month->format('M Y'),
                'sales' => (float) $sales,
                'orders' => $ordersCount,
                'target' => $sales * 1.2 // الهدف 20% أكثر من المبيعات
            ];
        }

        return $performance;
    }

    /**
     * تصدير تقرير المبيعات (اختياري)
     */
    public function exportReport()
    {
        // يمكن إضافة تصدير Excel أو PDF
    }
}
