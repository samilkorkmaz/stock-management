<?php
// app/Models/StockRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockRequest extends Model
{
    use HasFactory;

    protected $fillable = ['shop_id', 'item_id', 'quantity', 'status', 'allocated_quantity'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
