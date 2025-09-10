<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PasswordReset extends Model
{
    public $timestamps = false;
    
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'email',
        'otp',
        'created_at',
    ];

    protected $table = 'password_resets';

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
