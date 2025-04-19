<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapGenreTitle extends Model {
    use HasFactory;

    protected $table = 'map_genre_titles';

    protected $fillable = [
        'genre_id',
        'title_id'
    ];

    // Relation with Genre
    public function getGenre() {
        return $this->belongsTo(Genre::class, 'genre_id');
    }
}
