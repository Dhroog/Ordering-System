<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'restaurant_id',
        'main_id',
    ];

    public function Restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function main_category(): BelongsTo
    {
        return $this->belongsTo(Main_category::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

}
