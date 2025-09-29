<?php

namespace App\Models;

use Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function gameStamp()
    {
        return $this->belongsTo(GameStamp::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    // accessor untuk file_url
    protected $appends = ['thumbnail_url'];

    public function getThumbnailUrlAttribute()
    {
        if (!$this->thumbnail_path) {
            return null;
        }

        // Ambil base URL dari APP_URL .env
        $baseUrl = config('app.url');
        return $baseUrl . Storage::url($this->thumbnail_path);
    }
}
