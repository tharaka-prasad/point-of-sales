<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'member_id',
        'total_item',
        'total_price',
        'discount',
        'pay',
        'accepted',
        'user_id',
        'status',
        'product_ids',
        'return_products',
    ];
    protected $casts = [
        'product_ids'     => 'array',
        'return_products' => 'array',
    ];

    // Relation
    public function member()
    {
        return $this->hasOne(Member::class, "id", "member_id");
    }

    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
    }
    public function details()
    {
        return $this->hasMany(SaleDetail::class, "sale_id", "id");
    }
}
