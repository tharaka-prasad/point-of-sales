<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grn extends Model{
    use HasFactory;

    protected $fillable = [
        'grn_no',
        'supplier_id',
        'created_by',
        'po_no',
        'date',
        'invoice_no',
        'general_remarks',
        'grn_total',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($grn) {
            if (empty($grn->grn_no)) {
                $lastGrn     = self::latest('id')->first();
                $number      = $lastGrn ? $lastGrn->id + 1 : 1;
                $grn->grn_no = 'GRN-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function items()
    {
        return $this->hasMany(GrnItems::class, 'grn_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function getSupplierNameAttribute()
    {
        return $this->supplier ? $this->supplier->supplier_name : '-';
    }
    public function getCreatorNameAttribute()
    {
        return $this->creator ? $this->creator->name : '-';
    }

    public function getGrandTotalAttribute()
    {
        return $this->items->sum(fn($item) => $item->qty_accepted * $item->unit_price);
    }
    // newly added methods
    public function purchaseOrder()
{
    return $this->belongsTo(PurchaseOrder::class, 'po_id'); // po_id column in grn table
}

}
