<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'quantity'];

    public function stockRequests(): HasMany
    {
        return $this->hasMany(StockRequest::class);
    }
}
