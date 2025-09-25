<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrnItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'grn_item_id';

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
        'created_by',
        'description',
    ];

    // Relationship: GRN Item belongs to GRN
    public function grn()
    {
        return $this->belongsTo(Grn::class, 'grn_id');
    }

    // Relationship: GRN Item belongs to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Optional: automatically calculate total when unit_price or qty_received changes
    protected static function booted()
    {
        static::saving(function ($item) {
            if ($item->unit_price && $item->qty_received) {
                $item->total = $item->unit_price * $item->qty_received;
            }
        });
    }
}
