<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grn extends Model
{
    use HasFactory;

    protected $fillable = [
        'grn_no',
        'supplier_id',
        'created_by',
        'po_no',
        'date',
        'invoice_no',
        'general_remarks',
    ];

    // Automatically generate grn_no before creating
    protected static function booted()
    {
        static::creating(function ($grn) {
            if (empty($grn->grn_no)) {
                $lastGrn = self::latest('id')->first();
                $number = $lastGrn ? $lastGrn->id + 1 : 1;
                $grn->grn_no = 'GRN-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relationship: GRN has many items
    public function items()
    {
        return $this->hasMany(GrnItem::class, 'grn_id', 'id');
    }

    // Relationship: GRN belongs to supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relationship: GRN created by user
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
