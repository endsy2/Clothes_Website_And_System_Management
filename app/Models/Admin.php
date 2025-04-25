<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Extend Authenticatable
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable // This makes Admin a proper authentication model
{
    use HasFactory, Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'full_name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
