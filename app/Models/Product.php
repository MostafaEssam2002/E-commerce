<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
    //
    public function category()
    {
        return $this->belongsTo(categories::class, 'category_id');
    }
    public function productImages(){
        return $this->hasMany(productImages::class);
    }
    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }
    public function orderDetails(){
        return $this->hasMany(OrderDetail::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'sellerID');
        // كل product تابع ليوزر واحد
    }

}
