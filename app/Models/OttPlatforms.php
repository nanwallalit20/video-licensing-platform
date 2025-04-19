<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OttPlatforms extends Model
{
    use HasFactory;

    protected $table = 'ott_platforms';

    protected $fillable = [
        'name',
        'slug',
    ];
}
