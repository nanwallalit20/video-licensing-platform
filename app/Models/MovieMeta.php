<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieMeta extends Model {
    use HasFactory;

    protected $table = 'movie_metas';

    protected $fillable = [
        'release_date',
        'duration',
        'title_id'
    ];
}
