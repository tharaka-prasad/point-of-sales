<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'member_code',
        'name',
        'address',
        'phone',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class, 'member_id', 'id');
    }
}
