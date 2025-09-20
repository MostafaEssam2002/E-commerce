<?php

namespace App\Models;
use App\Models\products;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public function product()
    {
        return $this->belongsTo(products::class, 'product_id');
    }
}
