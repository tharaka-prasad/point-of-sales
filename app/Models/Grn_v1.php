<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class grn extends Model
{
    use HasFactory;

     protected $fillable = [
        'supplier_id',
        'purchase_order_id',
        'date',
        'total_amount',
        'tax_amount',
        'discount_amount',
        'grand_total',
        'status',
        'remarks',
        'created_by'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($grn) {
            $last = self::latest('id')->first();
            $number = $last ? $last->id + 1 : 1;
            $grn->grn_number = 'SHELL' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }
}
