<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    // App\Models\Order.php
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
}

}
