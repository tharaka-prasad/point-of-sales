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
        'supplier_name',
        'description',
        'contact_no',
        'quantity',
        'rate',
        'status'
    ];

}
