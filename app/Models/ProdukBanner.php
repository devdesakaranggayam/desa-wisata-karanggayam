<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukBanner extends Model
{
    protected $table = 'produk_banner';

    protected $guarded = ['id'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }
}
