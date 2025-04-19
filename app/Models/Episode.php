<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model {
    use HasFactory;

    protected $table = 'episodes';

    protected $fillable = [
        'season_id',
        'uuid',
        'name',
        'synopsis',
        'release_date',
    ];

    public function getMedia() {
        return $this->belongsToMany(Media::class, 'map_episode_media', 'episode_id', 'media_id')->withPivot('text');
    }
}
