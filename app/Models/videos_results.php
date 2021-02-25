<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class videos_results extends Model
{
    protected $fillable = ['description','time','video_id', 'confidence', 'path', 'quantity'];
}
