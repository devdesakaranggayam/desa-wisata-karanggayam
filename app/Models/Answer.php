<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Answer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
