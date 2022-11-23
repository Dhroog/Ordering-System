<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,\App\Traits\MustVerifyEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'lat',
        'lng',
        'email_verify_code',
        'email_verify_code_sent_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verify_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_verify_code_sent_at' => 'datetime',
    ];

    public function setPasswordAttribute($password){

        $this->attributes['password'] = Hash::make($password);
    }

    public function scopeWhereRole($query,$role)
    {
        if($role == 'admin')
            return $query->where('is_admin',1);
        if($role == 'client')
            return $query->where('is_admin',0);
        else
            return $query;
    }

    public function main_cart(): HasOne
    {
        return $this->hasOne(Main_cart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

}
