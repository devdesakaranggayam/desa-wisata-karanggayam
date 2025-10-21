<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $table = 'files';

    protected $guarded = ['id'];

    protected $appends = ['file_url'];

    public function fileable()
    {
        return $this->morphTo();
    }

    // accessor untuk file_url
    public function getFileUrlAttribute()
    {
        if (!$this->path) {
            return null;
        }

        return asset('storage' . '/' . $this->path);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
