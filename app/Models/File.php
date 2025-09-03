<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';

    protected $fillable = ['nama', 'tipe_file', 'path'];

    public function fileable()
    {
        return $this->morphTo();
    }
}
