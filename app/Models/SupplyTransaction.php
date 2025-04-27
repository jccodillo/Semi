<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'supply_id',
        'transaction_type', // 'receipt' or 'issuance'
        'quantity',
        'reference_number',
        'balance',
        'office',
        'days_to_consume'
    ];

    public function supply()
    {
        return $this->belongsTo(SuppliesInventory::class, 'supply_id');
    }
} 