<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'total',
        'restaurant_id',
        'tax',
        'delivery_cost',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Order_item::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
