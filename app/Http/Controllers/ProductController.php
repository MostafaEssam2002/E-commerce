<?php
namespace App\Http\Controllers;
use App\Models\categories;
use App\Models\Product;
use App\Models\productImages;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNumeric;

class ProductController extends Controller
{
    function add($num){
        return $num+1;
    }
    function productstable(){
        $products=Product::all();
        return view("products.productTable",["products"=>$products]);
    }

        function addproduct(){
            if(Auth::check()){
                $categories = categories::all();
            }
            else{
                $categories = categories::where("id",$this->add(1))->get();
            }
        return view("products.addproduct",["categories"=>$categories]);
    }
    function storeProduct (Request $request) {
    if ($request->id) {
        // حالة التعديل
        $request->validate([
            'name' => 'required|max:10|unique:products,name,'.$request->id,
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'description' => 'required',
            'category_id' => 'required',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $currentProduct = Product::find($request->id);
        if (!$currentProduct) {
            return redirect(route('prods'))->with('error', 'Product not found');
        }

        $currentProduct->name = $request->name;
        $currentProduct->price = $request->price;
        $currentProduct->quantity = $request->quantity;
        $currentProduct->shipping = $request->shipping;
        $currentProduct->description = $request->description;
        $currentProduct->category_id = $request->category_id;

        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imageName = Str::uuid()->toString().'_'.$image->getClientOriginalName();
            $destination = public_path('assets/img/products');
            $image->move($destination, $imageName);
            $currentProduct->image_path = 'assets/img/products/'.$imageName;
        }

        $currentProduct->save();
        return redirect("/editproduct/{$request->id}");

    } else {
        // حالة الإضافة
        $request->validate([
            'name' => 'required|max:10|unique:products',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'description' => 'required',
            'category_id' => 'required',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $newProduct = new Product();
        $newProduct->name = $request->name;
        $newProduct->price = $request->price;
        $newProduct->quantity = $request->quantity;
        $newProduct->shipping = $request->shipping;
        $newProduct->description = $request->description;
        $newProduct->category_id = $request->category_id;

        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imageName = Str::uuid()->toString().'_'.$image->getClientOriginalName();
            $destinationPath = public_path('assets/img/products');
            $image->move($destinationPath, $imageName);
            $newProduct->image_path = 'assets/img/products/'.$imageName;
        }

        $newProduct->save();
        return redirect("/addproduct");
    }
}

    function removeProduct($productid=null){
        if($productid !=null){
            $product = Product::find($productid);
            $product->delete();
            return redirect(route("prods"));
        }
        else{
            return view("layout.404")->with(['error'=>'Error Product Cannot be Found',"code"=>"404"]);
        }
    }
    public function editProduct ($productid=null){
        if($productid!=null){
            $product=Product::find($productid);
            $categories=categories::all();
            if ($product){
                return view("products.editproduct",["product"=>$product,"productid"=>$productid,"categories"=>$categories]);
            }else{
                return view("layout.404")->with(['error'=>'Error Product Cannot be Found',"code"=>"404"]);
            }
        }
        else{
            return view("layout.404")->with(['error'=>'Error Product Cannot be Found',"code"=>"404"]);
        }
    }
    public function edit_Product ($productid=null,Request $request){
        $request->validate([
            "name"=>["required","unique:products","max:10"],
            "price"=>["required","integer"],
            "quantity"=>["required","integer"],
            "description"=>"required",
            "category_id"=>"required"
        ]);
        if($productid!=null){
            $updated_product=Product::find($productid);
            $updated_product->name=$request->name;
            $updated_product->price=$request->price;
            $updated_product->quantity=$request->quantity;
            $updated_product->description=$request->description;
            $updated_product->category_id=$request->category_id;
            $updated_product->save();
            return redirect("/editproduct/{$productid}");
        }
        else{
            return view("layout.404")->with(['error'=>'Error Product Cannot be Found',"code"=>"404"]);
        }
    }
    public function AddProductImages($id){
        $product = Product::find($id);
        return view("products.AddProductImages",["product"=>$product]);
    }
    public function ShowProduct($id ){
        $id = (int )($id);
        if ($id>0){
            $product = Product::find($id);
            if($product){
                $related_products =Product::where("category_id",$product->category_id)->get();
                return view("products.single_product",["product"=>$product,"related_products"=>$related_products]);
            }else{
                return redirect("/");
            }
        }
        else{
            return redirect("/");
        }
    }
}
