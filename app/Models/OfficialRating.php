<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficialRating extends Model {
    use HasFactory;

    protected $table = 'official_ratings';

    protected $fillable = [
        'name',
    ];

    public function getTitles() {
        return $this->belongsToMany(Title::class, 'map_official_rating_titles', 'official_rating_id', 'title_id');
    }
}
