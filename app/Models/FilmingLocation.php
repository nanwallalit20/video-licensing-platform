<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilmingLocation extends Model {
    use HasFactory;

    protected $table = 'filming_locations';

    protected $fillable = [
        'title_id',
        'location'
    ];
}
