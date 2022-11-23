<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'description',
        'rate',
        'tax',
        'lat',
        'lng'
    ];

    public function scopeRateDescending($query)
    {
        return $query->orderBy('rate','DESC');
    }

    public function scopeNameDescending($query)
    {
        return $query->orderBy('name','DESC');
    }

    public function scopeOrder($query,$type)
    {
        if($type == 'rate')
            return $query->orderBy('rate','DESC');
        else
            return $query->orderBy('name','ASC');
    }

    public function category(): HasMany
    {
        return $this->hasMany(Category::class);
    }

}
