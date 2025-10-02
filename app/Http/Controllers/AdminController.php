<?php
namespace App\Http\Controllers;

use App\Models\categories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\categories_sales;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class AdminController extends Controller
{
    public function login(){
        if(Auth::check() && Auth::user()->role=="admin"){
            return view('admin_panal.login');
        }
        else{
            return view("layout.404")->with(['error'=>'You dont have permission',"code"=>"400"]);
        }
    }
    public function login_check(Request $request){
        try {
            if (Auth::user()->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ليس لديك صلاحية للوصول لهذه الصفحة'
                ], 403);
            }
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:6'
            ], [
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
                'password.required' => 'كلمة المرور مطلوبة',
                'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'خطأ في البيانات المدخلة',
                    'errors' => $validator->errors()
                ], 422);
            }
            $user = User::where("email", $request->email)->first();
            if ($user && Hash::check($request->password,$user->password)) {

                return response()->json([
                    'status' => 'success',
                    'redirect_url' => url('/admin/adminpanal'),
                    'message' => 'login successfully'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ في الخادم، يرجى المحاولة مرة أخرى'
            ], 500);
        }
    }
    public function adminpanal()
{
    $currentMonth = now()->month;
    $currentYear = now()->year;
    $previousMonth = now()->subMonth()->month;
    $previousMonthYear = now()->subMonth()->year;

    // إجمالي طلبات المستخدمين
    $total_users_orders = DB::table('orders')
        ->join('order_details', 'order_details.order_id', '=', 'orders.id')
        ->select(
            'orders.name',
            'order_details.order_id',
            DB::raw('SUM(order_details.price) as total_user_pay')
        )
        ->groupBy('orders.name', 'order_details.order_id')
        ->orderByDesc('total_user_pay')
        ->get();

    // الإيرادات
    $total_revenue_this_month = DB::table('order_details')
        ->whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->sum('price');

    $total_revenue_prev_month = DB::table('order_details')
        ->whereMonth('created_at', $previousMonth)
        ->whereYear('created_at', $previousMonthYear)
        ->sum('price');

    // المستخدمين
    $total_users = DB::table("users")->count('id');
    $total_users_this_month = DB::table("users")
        ->whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->count('id');

    $total_users_last_month = DB::table("users")
        ->whereMonth('created_at', $previousMonth)
        ->whereYear('created_at', $previousMonthYear)
        ->count('id');

    // الطلبات
    $total_orders = DB::table("orders")->count('id');
    $total_orders_this_month = DB::table("orders")
        ->whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->count('id');

    $total_orders_last_month = DB::table("orders")
        ->whereMonth('created_at', $previousMonth)
        ->whereYear('created_at', $previousMonthYear)
        ->count('id');

    // الزيارات
    $total_visits_this_month = DB::table("visits")
        ->whereMonth('visited_at', $currentMonth)
        ->whereYear('visited_at', $currentYear)
        ->count('id');

    $total_visits_last_month = DB::table("visits")
        ->whereMonth('visited_at', $previousMonth)
        ->whereYear('visited_at', $previousMonthYear)
        ->count('id');

    // معدل التحويل
    $Conversion_Rate = $total_visits_this_month > 0
        ? ($total_orders_this_month / $total_visits_this_month) * 100
        : 0;

    $Conversion_Rate_last_month = $total_visits_last_month > 0
        ? ($total_orders_last_month / $total_visits_last_month) * 100
        : 0;

    // الأرباح الشهرية (للسنة الحالية فقط)
    $profit_per_month = DB::table('order_details')
        ->selectRaw('MONTH(created_at) as month, SUM(price) as total_price')
        ->whereYear('created_at', $currentYear)
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->orderBy(DB::raw('MONTH(created_at)'))
        ->get();

    $monthlyProfits = array_fill(1, 12, 0);
    foreach ($profit_per_month as $row) {
        $monthlyProfits[$row->month] = $row->total_price;
    }

    // إجمالي المبيعات والأرباح
    $results = DB::table('order_totals_view')
        ->select(
            DB::raw('SUM(total_price) AS total_profit'),
            DB::raw('SUM(total_order) AS total_sales'),
            DB::raw('MONTH(created_at) as month')
        )
        ->whereYear('created_at', $currentYear)
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->get();

    // الفئات
    $categories = DB::table('categories_sales')->pluck('category_name');
    $categories_sold = DB::table('categories_sales')->pluck('total_orders');

    // المستخدمين الشهريين
    $monthlyUsers = DB::table('users')
        ->select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total_users')
        )
        ->whereYear('created_at', $currentYear)
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
        ->get();

    return view("admin_panal.home", [
        "result" => $total_users_orders,
        "total_revenue" => $total_revenue_this_month,
        "revenue_percentage" => $total_revenue_prev_month > 0
            ? (($total_revenue_this_month - $total_revenue_prev_month) / $total_revenue_prev_month) * 100
            : 0,
        "total_users" => $total_users,
        "user_percentage" => $total_users_last_month > 0
            ? (($total_users_this_month - $total_users_last_month) / $total_users_last_month) * 100
            : 0,
        "total_orders" => $total_orders,
        "order_percentage" => $total_orders_last_month > 0
            ? (($total_orders_this_month - $total_orders_last_month) / $total_orders_last_month) * 100
            : 0,
        "Conversion_Rate" => $Conversion_Rate,
        "Conversion_Rate_percentage" => $Conversion_Rate_last_month > 0
            ? (($Conversion_Rate - $Conversion_Rate_last_month) / $Conversion_Rate_last_month) * 100
            : 0,
        "total_profit" => $results->pluck('total_profit')->map(fn($val) => $val ?? 0)->toArray(),
        "total_sales" => $results->pluck('total_sales')->map(fn($val) => $val ?? 0)->toArray(),
        "categories" => $categories,
        "categories_sold" => $categories_sold,
        "monthlyUsers" => $monthlyUsers->pluck('total_users'),
        "monthlyProfits" => array_values($monthlyProfits),
    ]);
}
    public function users_table(){
        $users = User::all();
        return view('admin_panal.users', compact('users'));
    }
    public function show_users(){
        $users=User::all();
        return response()->json($users);
        // return view("admin_panal.users");
    }
    public function update_user(Request $request, $id){
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,'.$id,
    ]);

    $user = User::findOrFail($id);
    $user->name = $request->name;
    $user->email = $request->email;
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'User updated successfully',
        'user' => $user
    ]);
}
public function analytics(){
    return view("admin_panal.analytics");
}
// Delete user
public function delete_user($id){
    $user = User::findOrFail($id);
    $user->delete();

    return response()->json([
        'success' => true,
        'message' => 'User deleted successfully'
    ]);
}
public function addusers(){
    return view("admin_panal.add_users");
}
}
