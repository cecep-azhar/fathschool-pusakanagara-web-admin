<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingVideo extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'youtube_link',
        'thumbnail'
    ];
}
