<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameStamp extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    // accessor untuk file_url
    protected $appends = ['icon_url'];

    public function getIconUrlAttribute()
    {
        if (!$this->icon_path) {
            return null;
        }

        // Ambil base URL dari APP_URL .env
        $baseUrl = config('app.url');
        return $baseUrl . \Storage::url($this->icon_path);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
