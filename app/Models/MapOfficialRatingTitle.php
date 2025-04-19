<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapOfficialRatingTitle extends Model {
    use HasFactory;

    protected $table = 'map_official_rating_titles';

    protected $fillable = [
        'title_id',
        'official_rating_id'
    ];

    public function getOfficialRatings() {
        return $this->belongsTo(OfficialRating::class, 'official_rating_id');
    }

}
