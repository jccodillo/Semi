<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'iar_no',
        'supplier',
        'created_by',
        'total_amount',
        'remarks'
    ];

    /**
     * Get the items for this procurement
     */
    public function items()
    {
        return $this->hasMany(ProcurementItem::class);
    }

    /**
     * Get the user who created this procurement
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate a new IAR number in the format YYYYMM0001
     * The number continues across months rather than resetting
     */
    public static function generateIarNo()
    {
        $year = date('Y');
        $month = date('m');
        
        // Find the last IAR number for this year
        $lastIAR = self::where('iar_no', 'like', $year . '%')
                      ->orderBy('iar_no', 'desc')
                      ->first();
        
        if ($lastIAR) {
            // Extract the numeric part and increment
            $lastNumber = intval(substr($lastIAR->iar_no, 6));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $year . $month . $newNumber;
    }
}
