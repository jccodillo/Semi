<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'department',
        'branch',
        'quantity',
        'price',
        'category',
        'description',
        'control_number'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($stock) {
            $stock->control_number = static::generateControlNumber();
        });
    }

    protected static function generateControlNumber()
    {
        $prefix = 'STK';
        $year = date('Y');
        $month = date('m');
        
        // Get the last stock entry
        $lastStock = static::orderBy('id', 'desc')->first();
        
        if ($lastStock) {
            // Extract the numeric part and increment
            $lastNumber = intval(substr($lastStock->control_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}{$year}{$month}{$newNumber}";
    }

    public function getQrCodeAttribute()
    {
        // Instead of determining the URL based on the current user's role,
        // use a common entry point that will handle the role check dynamically
        $detailsUrl = url("/scan/item/{$this->id}");
        
        // Generate QR code with the URL
        return '<div class="qr-wrapper" title=\'' . htmlspecialchars($detailsUrl) . '\'>' . 
               QrCode::size(100)->generate($detailsUrl) .
               '</div>' .
               '<div class="modal fade qr-modal" id="qrModal' . $this->id . '" tabindex="-1" aria-labelledby="qrModalLabel' . $this->id . '" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="qrModalLabel' . $this->id . '">' . $this->product_name . ' - QR Code</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center p-4">
                                <div class="qr-code-large">' . 
                                    QrCode::size(400)->generate($detailsUrl) .
                                '</div>
                                <div class="mt-3">
                                    <p class="text-muted mb-0">Control Number: ' . $this->control_number . '</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>';
    }

    public function editHistories()
    {
        return $this->hasMany(StockEditHistory::class);
    }
}