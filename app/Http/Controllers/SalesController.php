<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\categories;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

        return view('admin_panal.sales', array_merge(
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
        ));
    }

    /**
     * الإحصائيات الأساسية
     */
    private function getBasicStats()
    {
        $totalSales = OrderDetail::sum(DB::raw('price * quantity'));
        $completedOrders = Order::where('status', 'completed')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $avgOrderValue = $completedOrders > 0 ? $totalSales / $completedOrders : 0;

        return compact('totalSales', 'completedOrders', 'pendingOrders', 'avgOrderValue');
    }

    /**
     * مقارنة الشهر الحالي بالشهر الماضي
     */
    private function getMonthlyComparisons()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lastMonth = now()->subMonth()->month;
        $lastMonthYear = now()->subMonth()->year;

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
     * المبيعات اليومية آخر 30 يوم
     */
    private function getDailySales()
    {
        return OrderDetail::whereHas('order', function($q) {
                $q->where('created_at', '>=', now()->subDays(30));
            })
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
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
        return Product::select('products.id', 'products.name', 'products.image_path')
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
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
        return categories::select('categories.id', 'categories.name', 'categories.image_path')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
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

        // التأكد من أن الترتيب آمن
        $allowedSorts = ['id', 'created_at', 'status'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'desc';

        $orders = Order::with(['user', 'orderDetails.product'])
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
        return User::select('users.id', 'users.name', 'users.email', 'users.avatar')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
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

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);

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
