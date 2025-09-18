<?php

// app/Models/Kesenian.php
namespace App\Models;

use App\Models\File;
use App\Models\Stats;
use Illuminate\Database\Eloquent\Model;

class Kesenian extends Model
{
    protected $table = 'kesenian';

    protected $guarded = ['id'];

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
    
    public function stat()
    {
        return $this->morphOne(Stats::class, 'statable');
    }
}
