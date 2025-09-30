<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hadiah extends Model
{
    protected $table = 'hadiah';
    protected $guarded = ['id'];

    public function thumbnail()
    {
        return $this->morphOne(File::class, 'fileable');
    }
}
