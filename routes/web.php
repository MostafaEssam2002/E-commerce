<?php
use App\Http\Controllers\FirstController;
use App\Http\Controllers\ProductController;
// use App\Models\products;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/',[FirstController::class,'MainPage']);
Route::get("/category",[FirstController::class,'GetAllCategoriesWithProducts'])->name("cats");
Route::get("/product/{catid?}",[FirstController::class,"GetCategoryProduct"])->name("prods");
Route::get("/reviews",[FirstController::class,'reviews'])->name("reviews");
Route::post("/storereview",[FirstController::class,'storeReview'])->name("storereview");
Route::post("/search",[FirstController::class,"searchForProduct"])->name("search");


Route::get("/addproduct",[ProductController::class,'addproduct'])->name("addproduct")->middleware("auth");
// Route::get("/addproduct",[ProductController::class,'addproduct'])->name("addproduct");
Route::get('/removeproduct/{productid}',[ProductController::class,'removeProduct'])->name("removeproduct");
Route::post('/storeproduct/{productid?}',[ProductController::class,'storeProduct'])->name("storeproduct");
Route::get('/editproduct/{productid}', [ProductController::class, 'editProduct'])->name("editproduct");
Route::post('/editproduct/{productid}', [ProductController::class, 'storeProduct']);
Route::get("/productstable",[ProductController::class,'productstable'])->name("productstable");

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
