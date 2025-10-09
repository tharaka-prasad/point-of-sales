<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'supplier_name',
        'company_name',
        'name',# this is for category in db table also
        'phone',
        'address'
    ];


}
