<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    //
    public function category()
    {
        return $this->belongsTo(categories::class, 'category_id');
    }
}
