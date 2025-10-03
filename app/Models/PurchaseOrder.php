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
        'purchase_company',
        'supplier_id',
        'description',
        'contact_no',
        'quantity',
        'rate',
        'status'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    public function getSupplierNameAttribute()
    {
        return $this->supplier ? $this->supplier->supplier_name : '-';
    }

}
