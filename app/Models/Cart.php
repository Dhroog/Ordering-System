<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'main_cart_id',
        'restaurant_id',
        'tax',
        'delivery_cost'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Cart_item::class,);
    }


    public function main_cart()
    {
        return $this->belongsTo(Main_cart::class);
    }
}
