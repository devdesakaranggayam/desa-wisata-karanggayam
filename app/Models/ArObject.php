<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArObject extends Model
{
    use HasFactory;

    // Guard the ID field
    protected $guarded = ['id'];
    public $incrementing = false;  // non-auto increment

    protected $appends = ['audio_url', 'object_url'];

    public function getAudioUrlAttribute()
    {
        return asset('storage/' . $this->audio_path);
    }

    public function getObjectUrlAttribute()
    {
        return asset('storage/' . $this->{'3d_path'});
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
