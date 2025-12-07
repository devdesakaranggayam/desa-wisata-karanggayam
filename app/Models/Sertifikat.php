<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    protected $guarded = ['id'];
    protected $table = 'sertifikat';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
