<?php

namespace App\Models;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    protected $table = 'toko';
    protected $guarded = ['id'];

    public function products()
    {
        return $this->hasMany(Produk::class, 'toko_id');
    }
}
