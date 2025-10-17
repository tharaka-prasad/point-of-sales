<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'company_name',
        'category_id',
        'address',
        'phone',
    ];

    // ✅ Correct relationship: Supplier belongs to Category
    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id', 'name');
    }

    // ✅ Accessor for category name
    public function getCategoryNameAttribute(){
        return $this->category ? $this->category->name : '-';
    }
}
