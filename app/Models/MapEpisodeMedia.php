<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapEpisodeMedia extends Model {
    use HasFactory;

    protected $table = 'map_episode_media';

    protected $fillable = [
        'episode_id',
        'media_id',
        'text'
    ];

    public function getMedia() {
        return $this->belongsTo(Media::class, 'media_id', 'id');
    }

}
