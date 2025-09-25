<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'supplier_name',
        'company_name',
        'name',
        'address',
        'phone',
    ];
}
