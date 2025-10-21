<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'pos';

    protected $fillable = [
        'po_number',
        'supplier_id',
        'description',
        'total_price',
        'grand_total',
        'rate',
        'status'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(product::class, 'product_id', 'id');
    }

    public function getSupplierNameAttribute()
    {
        return $this->supplier ? $this->supplier->supplier_name : '-';
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id', 'id');
    }
    public function getNextPoNumber(){
        
        $lastPO = PurchaseOrder::latest('id')->first();
        $lastNumber = 99; // start from 100 if no PO exists

        if ($lastPO) {
            $parts = explode('-', $lastPO->po_number);
            $lastNumber = (int) end($parts);
        }

        $nextNumber = $lastNumber + 1;
        $d = now();
        $poNumber = 'PO-' . $d->format('Ymd') . '-' . $nextNumber;

        return response()->json(['nextPoNumber' => $poNumber]);
    }

}
