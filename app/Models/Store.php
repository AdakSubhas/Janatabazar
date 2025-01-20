<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Store extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'store_name',
        'username',
        'password',
        'mobile',
        'email',
        'address',
        'zip_code',
        'image',
        'active_stats',
        'status',
        'created_at',
        'updated_at',
    ];
}
