<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStamp extends Model
{
    protected $guarded = ['id'];

    // relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relasi ke GameStamp
    public function gameStamp()
    {
        return $this->belongsTo(GameStamp::class);
    }
}
