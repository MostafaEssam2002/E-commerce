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
}
