<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Main_category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function category(): HasMany
    {
        return $this->hasMany(Category::class,'main_id');
    }

}
