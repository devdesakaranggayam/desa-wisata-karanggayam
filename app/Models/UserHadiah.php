<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserHadiah extends Model
{
    protected $guarded = ['id'];
    protected $table = 'user_hadiah';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
