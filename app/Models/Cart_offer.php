<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart_offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
    ];
}
