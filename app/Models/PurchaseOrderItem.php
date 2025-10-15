<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem  extends Model
{
    use HasFactory;

    // ✅ Make sure this table name matches your actual DB table for items
    protected $table = 'po_items';
    protected $primaryKey = 'purchase_order_id';

    protected $fillable = [
        'id',
        'item_name',
        'category',
        'uom',
        'qty',
        'rate',
        'total',
        'remarks',
        'purchase_order_id',

    ];
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(product::class, 'product_id', 'id');
    }
     public function getItemNameAttribute()
    {
        return $this->product ? $this->product->product_name : '-';
    }


    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }
}
