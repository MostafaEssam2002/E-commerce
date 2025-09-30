<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
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
                    // redirect_url
                    'redirect_url' => url('/adminpanal'),
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
    public function adminpanal(){
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
        $total_revenue_this_month = DB::table('order_details')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('price');
        $total_revenue_prev_month = DB::table('order_details')->whereMonth('created_at', date('m')-1)->whereYear('created_at', date('Y'))->sum('price');
        $total_users = DB::table("users")->count('id');
        $total_users_this_month = DB::table("users")->whereMonth('created_at',date('m'))->whereYear('created_at',date('Y'))->count('id');
        $total_users_last_month = DB::table("users")->whereMonth('created_at',date('m')-1)->whereYear('created_at',date('Y'))->count('id');
        $total_orders = DB::table("orders")->count('id');
        $total_orders_this_month = DB::table("orders")->whereMonth('created_at',date('m'))->whereYear('created_at',date('Y'))->count('id');
        $total_orders_last_month = DB::table("orders")->whereMonth('created_at',date('m')-1)->whereYear('created_at',date('Y'))->count('id');
        // $total_visits = DB::table("visits")->count('id');
        $total_visits_this_month = DB::table("visits")->whereMonth('visited_at',date('m'))->whereYear('visited_at',date('Y'))->count('id');
        $total_visits_last_month = DB::table("visits")->whereMonth('visited_at',date('m')-1)->whereYear('visited_at',date('Y'))->count('id');
        $Conversion_Rate  = ($total_orders_this_month/$total_visits_this_month)*100 ;
        $Conversion_Rate_last_month  = ($total_orders_last_month/$total_visits_last_month)*100 ;
        return view("admin_panal.home", [
            "result"=> $total_users_orders,
            "total_revenue"=> $total_revenue_this_month,
            "revenue_percentage"=> $total_revenue_prev_month > 0 ? (($total_revenue_this_month - $total_revenue_prev_month) / $total_revenue_prev_month) * 100: 0,
            "total_users"=> $total_users,
            "user_percentage"=> $total_users_last_month > 0 ? (($total_users_this_month - $total_users_last_month) / $total_users_last_month) * 100 : 0,
            "total_orders"=> $total_orders,
            "order_percentage"=> $total_orders_last_month > 0 ? (($total_orders_this_month - $total_orders_last_month) / $total_orders_last_month) * 100 : 0,
            "Conversion_Rate"=>$Conversion_Rate,
            "Conversion_Rate_percentage"=> $Conversion_Rate_last_month > 0 ? (($Conversion_Rate - $Conversion_Rate_last_month) / $Conversion_Rate_last_month) * 100 : 0,
        ]);
    }
}
