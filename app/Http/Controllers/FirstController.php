<?php
namespace App\Http\Controllers;
use App\Models\categories;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class FirstController extends Controller
{
    function MainPage(){
        if(Auth::check()){
            $result = Product::paginate(6);
        }
        else{
            $result = Product::paginate(3);
        }
        return view("welcome",["categories"=>$result]);
    }
    function GetCategoryProduct ($catid=null) {
        if ($catid!=null and is_numeric($catid)) {
            $result=Product::where("category_id",$catid)->get();
            return view("product",["products"=>$result]);
        }
        elseif($catid==null){
            $result=Product::all();
            return view("product",["products"=>$result]);
        }
        else{
            return view('layout.404')->with(['error'=>'Enter valid id',"code"=>"400"]);
        }
    }
    function GetAllCategoriesWithProducts(){
        $products=Product::all();
        $categories=categories::all();
        return view("category",["products"=>$products,"categories"=>$categories]);
    }
    function reviews(){
        $reviews = Review::all();
        return view("reviews",["reviews"=>$reviews]);
    }
    function storeReview(Request $request){
        $review = new Review();
        $request->validate([
            "name"=>"required",
            "phone"=>"required|max:11",
            "email"=>"required",
            "subject"=>"required",
            "content"=>"required",
        ]);
        $review->name=$request->name;
        $review->phone=$request->phone;
        $review->email=$request->email;
        $review->subject=$request->subject;
        $review->message=$request->content;
        $review->save();
        return redirect(route("reviews"));
    }
    function searchForProduct(Request $request)
    {
        $request->validate([
            "catname" => "required|string"
        ]);
        $catname = $request->input('catname');
        $category = categories::where("name","LIKE","%$catname%")->get();
        if (!$category) {
            return view("layout.404")->with(['error' => 'Category not found',"code"  => "300"]);
        }
        $products = Product::with('category')->whereIn("category_id", $category->pluck('id'))->orderBy('created_at','desc')->get();
        return view("product", ["products" => $products,"catname"  => $category->pluck("name")]);
    }
}
