<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price'
    ];

    protected $hidden = ['pivot'];

    public function items()
    {
        return $this->belongsToMany(
            Item::class,
            'offer_items',
            'offer_id',
            'item_id');
    }

}
