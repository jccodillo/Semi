<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'procurement_id',
        'stock_no',
        'product_name',
        'description',
        'quantity',
        'unit_type',
        'price_per_unit',
        'total_amount'
    ];

    /**
     * Get the procurement that owns the item
     */
    public function procurement()
    {
        return $this->belongsTo(Procurement::class);
    }

    /**
     * Calculate the total amount before saving
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->total_amount = $item->quantity * $item->price_per_unit;
        });

        static::updating(function ($item) {
            $item->total_amount = $item->quantity * $item->price_per_unit;
        });
    }
}
