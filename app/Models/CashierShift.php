<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashierShift extends Model
{
     use HasFactory;

    protected $fillable = [
        'cashier_id',
        'start_time',
        'end_time',
        'start_balance',
        'end_balance',
        'total_amount',
        'created_by',
    ];

    /**
     * Relationship to the cashier (User).
     */
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    /**
     * Relationship to the creator (User who started/created the shift).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
