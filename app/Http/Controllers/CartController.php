<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Copon;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Redirect;
class CartController extends Controller
{
    function cart(){
        $cart = Cart::where("user_id", Auth::user()->id)->get();
        return view("products.cart", [
            "cart" => $cart,
            "count" => $cart->count(),
            "finalTotalAfterCopon" => null,
            "finalTotal" => $cart->sum(fn($item) => $item->quantity * $item->product->price)
        ]);
    }
    function remove_from_cart(Request $request){
        try {
            $user_id = Auth::user()->id;
            $product_id = $request->productid;
            $cart = Cart::where("user_id", $user_id)->where("product_id", $product_id)->first();
            if ($cart) {
                $cart->delete();
                // Get updated cart count
                $cartCount = Cart::where("user_id", $user_id)->count();
                return response()->json([
                    'success' => true,
                    'message' => 'Product removed from cart successfully!',
                    'cart_count' => $cartCount
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found in cart'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    function add_to_cart(Request $request){
    $request->validate([
        "product_id" => "required|numeric|exists:products,id"
    ]);
    $user_id = Auth::user()->id;
    $product_id = $request->product_id;
    $existingCart = Cart::where('user_id', $user_id)
                        ->where('product_id', $product_id)
                        ->first();
    if ($existingCart) {
        $existingCart->quantity += 1;
        $existingCart->save(); // لازم تحفظ التغيير
        $cartCount = Cart::where("user_id", $user_id)->sum("quantity");
        return response()->json([
            'success' => true,
            'message' => 'Product already in cart! Quantity updated',
            'cart_count' => $cartCount,
            'already_exists' => true
        ]);
    } else {
        $cart = new Cart();
        $cart->user_id = $user_id;
        $cart->product_id = $product_id;
        $cart->quantity = 1;
        $cart->save();
        $cartCount = Cart::where("user_id", $user_id)->sum("quantity");
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'quantity' => 1,
            'cart_count' => $cartCount
        ]);
    }
}
    function getCartCount(){
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }
        $count = Cart::where("user_id", Auth::user()->id)->count();
        return response()->json(['count' => $count]);
    }
    function change_quantity(Request $request){
        try {
            $quantity = $request->quantity;
            $productid = $request->productid;
            $user_id = Auth::user()->id;
            // If quantity is 0, delete the item
            if ($quantity == 0) {
                $cart = Cart::where("user_id", $user_id)->where("product_id", $productid)->first();
                if ($cart) {
                    $cart->delete();
                    $cartCount = Cart::where("user_id", $user_id)->count();
                    return response()->json([
                        'success' => true,
                        'message' => 'Product removed from cart successfully!',
                        'deleted' => true,
                        'cart_count' => $cartCount
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cart item not found'
                    ]);
                }
            }
            if ($quantity < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantity must be at least 1'
                ]);
            }
            $cart = Cart::where("user_id", $user_id)->where("product_id", $productid)->first();
            if ($cart) {
                $cart->quantity = $quantity;
                $cart->save();
                $newTotal = $cart->quantity * $cart->product->price;
                $cartCount = Cart::where("user_id", $user_id)->count();
                return response()->json([
                    'success' => true,
                    'message' => 'quantity updated successfully',
                    'new_quantity' => $cart->quantity,
                    'new_total' => $newTotal,
                    'product_price' => $cart->product->price,
                    'cart_count' => $cartCount
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }
    function copon(Request $request){
        $request->validate([
            "copon" => "required|string"
        ]);
        $copon = $request->copon;
        $copons=Copon::where("copon",$copon)->first();
        if($copons){
            $finalTotal=$request->total - $copons->value;
            if ($finalTotal <0) {
                $finalTotal = 0;
            }
            return redirect()->route("cart")->with(["finalTotalAfterCopon"=>$finalTotal]);
        }else{
            return redirect()->route("cart")->with(["error"=>"invalid copon"]);
        }
    }
    public function completeorder(){
        $cart = Cart::where("user_id", Auth::user()->id)->get();
        return view("products.completeorder",["cart"=>$cart]);
    }
    public function storeorder(Request $request){
        $request->validate([
            "name" => "required|string",
            "address" => "required|string",
            "phone" => "required|string",
            "email" => "required|email"
        ]);
        $order = new Order();
        $user_id = Auth::user()->id;
        $order->user_id = $user_id;
        $order->name = $request->name;
        $order->address = $request->address;
        $order->phone = $request->phone;
        $order->email = $request->email;
        $order->note = $request->note;
        $cartitems = Cart::where("user_id",$user_id)->get();
        $order->save();
        foreach($cartitems as $item){
            $order_detalis = new OrderDetail();
            $order_detalis->order_id = $order->id;
            $order_detalis->product_id = $item->product_id;
            $order_detalis->quantity = $item->quantity;
            $order_detalis->price = $item->product->price;
            $order_detalis->save();
        }
        Cart::where("user_id", $user_id)->delete();
        return back();
    }
    public function lastorders(){
        $user_id =Auth::user()->id; ;
        // $last_orders = Order::where("user_id",Auth::user()->id)->get();
        if($user_id == 1){
            $last_orders = Order::with("orderDetails")->get();
            return view("products.lastorders",["last_orders"=>$last_orders]);
        }
        // 17
        elseif($user_id > 1){
            $last_orders = Order::with("orderDetails")->where("user_id",$user_id)->get();
            return view("products.lastorders",["last_orders"=>$last_orders]);
        }
        else{
            return back();
        }
    }
}
