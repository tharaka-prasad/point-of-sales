<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'grn_id',
        'product_id',
        'uom',
        'qty_ordered',
        'qty_received',
        'qty_accepted',
        'qty_rejected',
        'unit_price',
        'total',
        'remarks',
        'status',
    ];

    // Relationships
    public function grn()
    {
        return $this->belongsTo(Grn::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
