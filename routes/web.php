<?php
use App\Http\Controllers\FirstController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get("/test",[TestController::class, 'test']);//->middleware("CheckRole:seller");
// Public Routes
Route::get('/', [FirstController::class, 'MainPage'])->middleware('customauth');
Route::get("/category", [FirstController::class, 'GetAllCategoriesWithProducts'])->name("cats");
Route::get("/category/{catid?}", [FirstController::class, "GetCategoryProduct"])->name("prods");
Route::get("/reviews", [FirstController::class, 'reviews'])->name("reviews");
Route::post("/storereview", [FirstController::class, 'storeReview'])->name("storereview");
Route::post("/search", [FirstController::class, "searchForProduct"])->name("search");

// Product Routes
Route::get("/product/{id}", [ProductController::class, "ShowProduct"])->name("ShowProduct");
Route::get("/productstable", [ProductController::class, 'productstable'])->name("productstable");

// Auth Routes
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get("/products", [ProductController::class, 'products'])->name("products");
// Protected Routes (Require Authentication)
Route::middleware(['auth','CheckRole:seller,admin'])->group(function () {
    Route::get("/addproduct", [ProductController::class, 'addproduct'])->name("addproduct");
    Route::get('/removeproduct/{productid}', [ProductController::class, 'removeProduct'])->name("removeproduct");
    Route::post('/storeproduct/{productid?}', [ProductController::class, 'storeProduct'])->name("storeproduct");
    Route::get('/editproduct/{productid}', [ProductController::class, 'editProduct'])->name("editproduct");
    Route::post('/editproduct/{productid}', [ProductController::class, 'storeProduct']);
    Route::get("/AddProductImages/{productid}", [ProductController::class, "AddProductImages"])->name("AddProductImages");
    Route::get("/removeproductimage/{id}",[ProductController::class,"removeproductimage"])->name("removeproductimage");
    Route::post("/add_product_image/{product_id}",[ProductController::class,"add_product_image"])->name("add_product_image");
});

Route::middleware('auth')->group(function () {
    // Product Management Routes
    // Route::get("/products", [ProductController::class, 'products'])->name("products");
    // Route::get("/addproduct", [ProductController::class, 'addproduct'])->name("addproduct");
    // Route::get('/removeproduct/{productid}', [ProductController::class, 'removeProduct'])->name("removeproduct");
    // Route::post('/storeproduct/{productid?}', [ProductController::class, 'storeProduct'])->name("storeproduct");
    // Route::get('/editproduct/{productid}', [ProductController::class, 'editProduct'])->name("editproduct");
    // Route::post('/editproduct/{productid}', [ProductController::class, 'storeProduct']);
    // Route::get("/AddProductImages/{productid}", [ProductController::class, "AddProductImages"])->name("AddProductImages");
    // Route::get("/removeproductimage/{id}",[ProductController::class,"removeproductimage"])->name("removeproductimage");
    // Route::post("/add_product_image/{product_id}",[ProductController::class,"add_product_image"])->name("add_product_image");
    // Cart Routes
    Route::get("/cart", [CartController::class, 'cart'])->name("cart");
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
    Route::post("/add_to_cart", [CartController::class, 'add_to_cart'])->name("add_to_cart");
    Route::post("/remove_from_cart", [CartController::class, 'remove_from_cart'])->name("remove_from_cart");
    Route::post("/change_quantity", [CartController::class, 'change_quantity'])->name("change_quantity");
    Route::post("/copon", [CartController::class, "copon"])->name("copon");
    Route::get("/completeorder",[CartController::class,"completeorder"])->name("completeorder");
    Route::post("/storeorder",[CartController::class,"storeorder"])->name("storeorder");
    Route::get("/lastorders",[CartController::class,"lastorders"])->name("lastorders");
});
Route::post('/change_lang', [FirstController::class, 'change_lang'])->name('change_lang');
// Route::get('/test');
// change_lang
// use App\Http\Controllers\FirstController;
// use App\Http\Controllers\ProductController;
// use App\Http\Controllers\CartController;
// // use App\Models\products;
// // use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Auth;

// Route::get('/',[FirstController::class,'MainPage']);
// Route::get("/category",[FirstController::class,'GetAllCategoriesWithProducts'])->name("cats");
// Route::get("/category/{catid?}",[FirstController::class,"GetCategoryProduct"])->name("prods");
// Route::get("/reviews",[FirstController::class,'reviews'])->name("reviews");
// Route::post("/storereview",[FirstController::class,'storeReview'])->name("storereview");
// Route::post("/search",[FirstController::class,"searchForProduct"])->name("search");


// Route::get("/addproduct",[ProductController::class,'addproduct'])->name("addproduct")->middleware("auth");
// // Route::get("/addproduct",[ProductController::class,'addproduct'])->name("addproduct");
// Route::get("/product/{id}",[ProductController::class,"ShowProduct"])->name("ShowProduct");
// Route::get('/removeproduct/{productid}',[ProductController::class,'removeProduct'])->name("removeproduct");
// Route::post('/storeproduct/{productid?}',[ProductController::class,'storeProduct'])->name("storeproduct");
// Route::get('/editproduct/{productid}', [ProductController::class, 'editProduct'])->name("editproduct");
// Route::post('/editproduct/{productid}', [ProductController::class, 'storeProduct']);
// Route::get("/productstable",[ProductController::class,'productstable'])->name("productstable");
// Route::get("/AddProductImages/{productid}",[ProductController::class,"AddProductImages"])->name("AddProductImages");


// Route::get("/cart",[CartController::class,'cart'])->name("cart")->middleware("auth");
// Route::post("/change_quantity",[CartController::class,'change_quantity'])->name("change_quantity")->middleware("auth");
// Route::post("/add_to_cart",[CartController::class,'add_to_cart'])->name("add_to_cart")->middleware("auth");
// Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
// Route::post('/add-to-cart', [CartController::class, 'add_to_cart'])->name('add_to_cart');
// Route::post('/remove-from-cart', [CartController::class, 'remove_from_cart'])->name('remove_from_cart');
// Route::post("/change-quantity", [CartController::class, 'change_quantity'])->name('change_quantity')->middleware('auth');
// Route::post("/copon",[CartController::class,"copon"])->name("copon")->middleware("auth");
// // Add these routes to your web.php file

// Route::middleware('auth')->group(function () {
//     Route::post('/add-to-cart', [CartController::class, 'add_to_cart'])->name('add_to_cart');
//     Route::post('/remove-from-cart', [CartController::class, 'remove_from_cart'])->name('remove_from_cart');
//     Route::post('/change-quantity', [CartController::class, 'change_quantity'])->name('change_quantity');
//     Route::get('/cart-count', [CartController::class, 'getCartCount'])->name('cart.count');
//     Route::get('/cart', [CartController::class, 'cart'])->name('cart');
//     Route::post('/copon', [CartController::class, 'copon'])->name('copon');
// });
// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
