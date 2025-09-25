<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number', 'purchase_company', 'description', 'quantity', 'rate', 'total', 'issue_date', 'issue_time'
    ];

    // Generate PO number automatically
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($po) {
            $lastPo = Po::latest('id')->first();
            $poNumber = $lastPo ? 1000 + $lastPo->id + 1 : 1000;
            $po->po_number = 'PO-' . $poNumber;
            $po->issue_date = date('Y-m-d'); // today
            $po->issue_time = date('H:i:s'); // current time
            $po->total = $po->quantity * $po->rate; // calculate total
        });
    }
}
