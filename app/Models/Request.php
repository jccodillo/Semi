<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
        'request_id',
        'control_number',
        'product_name',
        'department',
        'branch',
        'quantity',
        'price',
        'category',
        'description',
        'status',
        'remarks',
        'user_id',
        'approved_by',
        'approved_at',
        'timestamps'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($request) {
            // Only generate request_id if not already set
            if (empty($request->request_id)) {
                // Generate request_id (YYYYMMDD-XXXX)
                $today = now()->setTimezone('Asia/Manila');
                $dateString = $today->format('Ymd');
                
                // Find the latest request with the same date prefix
                $latest = static::whereRaw("LEFT(request_id, 8) = ?", [$dateString])
                    ->orderByRaw("CAST(SUBSTRING(request_id, 10) AS UNSIGNED) DESC")
                    ->first();
                
                $number = $latest ? (int)substr($latest->request_id, -4) + 1 : 1;
                
                // Ensure uniqueness with a loop
                $isUnique = false;
                $attempts = 0;
                $maxAttempts = 10;
                
                while (!$isUnique && $attempts < $maxAttempts) {
                    $requestId = $dateString . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
                    
                    if (!static::where('request_id', $requestId)->exists()) {
                        $isUnique = true;
                        $request->request_id = $requestId;
                    } else {
                        $number++;
                        $attempts++;
                    }
                }
                
                if (!$isUnique) {
                    throw new \Exception("Could not generate a unique request_id after {$maxAttempts} attempts.");
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    // Add relationship to StockRequestItem
    public function items()
    {
        return $this->hasMany(StockRequestItem::class, 'stock_request_id', 'request_id');
    }
    
    // Scope to get requests grouped by request_id
    public function scopeGrouped($query)
    {
        return $query->select('request_id')
                    ->groupBy('request_id')
                    ->orderBy('created_at', 'desc');
    }
    
    // Get all items with the same request_id
    public function relatedItems()
    {
        return $this->hasMany(Request::class, 'request_id', 'request_id');
    }
}