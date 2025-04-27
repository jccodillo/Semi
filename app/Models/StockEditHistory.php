<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockEditHistory extends Model
{
    protected $fillable = ['stock_id', 'changes', 'reason', 'edited_by'];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
}
