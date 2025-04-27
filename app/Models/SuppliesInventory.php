<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuppliesInventory extends Model
{
    use HasFactory;

    protected $table = 'supplies_inventory';

    protected $fillable = [
        'control_code',
        'product_name',
        'quantity',
        'unit_type',
        'product_image',
        'description'
    ];
    
    /**
     * Scope for finding an item by product name and unit type
     */
    public function scopeFindByProductAndUnit($query, $productName, $unitType)
    {
        return $query->where('product_name', $productName)
                     ->where('unit_type', $unitType);
    }
}