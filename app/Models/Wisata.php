<?php

namespace App\Models;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    protected $table = 'wisata';

    protected $guarded = ['id'];

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
    
    public function stat()
    {
        return $this->morphOne(Stats::class, 'statable');
    }

    public function scopeWisata($query)
    {
        return $query->where('type', 'wisata');
    }

    public function scopeKesenian($query)
    {
        return $query->where('type', 'kesenian');
    }
}
