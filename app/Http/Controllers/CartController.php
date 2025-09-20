<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    function cart(){
        $cart = Cart::where("user_id", Auth::user()->id)->get();
        return view("products.cart", ["cart" => $cart]);
    }

    function remove_from_cart(Request $request){
        try {
            $user_id = Auth::user()->id;
            $product_id = $request->productid;

            $cart = Cart::where("user_id", $user_id)->where("product_id", $product_id)->first();

            if ($cart) {
                $cart->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Product removed from cart successfully!'
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

        // Check if product already exists in cart
        $existingCart = Cart::where('user_id', $user_id)
                           ->where('product_id', $product_id)
                           ->first();

        if ($existingCart) {
            // If product exists, increment quantity
            $existingCart->quantity += 1;
            $existingCart->save();

            return response()->json([
                'success' => true,
                'message' => 'Product quantity updated in cart!',
                'quantity' => $existingCart->quantity
            ]);
        } else {
            // If product doesn't exist, create new cart item
            $cart = new Cart();
            $cart->user_id = $user_id;
            $cart->product_id = $product_id;
            $cart->quantity = 1;
            $cart->save();

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'quantity' => 1
            ]);
        }
    }

    function getCartCount(){
        $count = Cart::where("user_id", Auth::user()->id)->sum('quantity');
        return response()->json(['count' => $count]);
    }

    function change_quantity(Request $request){
        try {
            $quantity = $request->quantity;
            $productid = $request->productid;

            // If quantity is 0, delete the item
            if ($quantity == 0) {
                $cart = Cart::where("user_id", Auth::user()->id)->where("product_id", $productid)->first();
                if ($cart) {
                    $cart->delete();
                    return response()->json([
                        'success' => true,
                        'message' => 'Product removed from cart successfully!',
                        'deleted' => true
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

            $cart = Cart::where("user_id", Auth::user()->id)->where("product_id", $productid)->first();
            if ($cart) {
                $cart->quantity = $quantity;
                $cart->save();
                $newTotal = $cart->quantity * $cart->product->price;
                return response()->json([
                    'success' => true,
                    'message' => 'quantity updated successfully',
                    'new_quantity' => $cart->quantity,
                    'new_total' => $newTotal,
                    'product_price' => $cart->product->price
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
}
