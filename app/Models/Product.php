<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'brand',
        'price',
        'discount',
        'sell_price',
        'stock',
        'expiry_date',
        'batch_no'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
