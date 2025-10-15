<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model{

    protected $fillable = [
        'supplier_name',
        'company_name',
        'category_id',
        'address',
        'phone',
    ];

    public function category(){
        // assuming 'category_id' column exists in suppliers table
        return $this->belongsTo(Category::class, 'category_id');
    }
}
