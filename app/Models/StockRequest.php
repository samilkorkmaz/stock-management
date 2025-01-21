<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockRequest extends Model
{
    use HasFactory;

    protected $fillable = ['shop_id', 'item_id', 'quantity', 'status', 'allocated_quantity'];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
