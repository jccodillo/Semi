<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockRequestItem extends Model
{
    protected $fillable = [
        'stock_request_id',
        'product_name',
        'quantity',
        'price',
        'category',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the request that owns the item.
     */
    public function request()
    {
        return $this->belongsTo(Request::class, 'stock_request_id', 'request_id');
    }

    public function requestByRequestId()
    {
        return $this->belongsTo(Request::class, 'request_id', 'request_id');
    }
} 