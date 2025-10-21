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
    protected $appends = ['icon_url', 'icon_stamp_url'];

    public function getIconUrlAttribute()
    {
        if (!$this->icon_path) {
            return null;
        }

        return asset('storage' . '/' . $this->icon_path);
    }

    public function getIconStampUrlAttribute()
    {
        if (!$this->icon_stamp_path) {
            return asset('storage' . '/' . $this->icon_path);
        }

        return asset('storage' . '/' . $this->icon_stamp_path);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
