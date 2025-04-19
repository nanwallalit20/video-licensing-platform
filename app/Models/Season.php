<?php

namespace App\Models;

use App\Enums\MediaTypes;
use App\Helpers\FileUploadHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model {
    use HasFactory;

    protected $table = 'seasons';

    protected $fillable = [
        'name',
        'uuid',
        'synopsis',
        'release_date',
        'title_id'
    ];

    public function getMedia() {
        return $this->belongsToMany(Media::class, 'season_media', 'season_id', 'media_id');
    }

    public function getEpisodes() {
        return $this->hasMany(Episode::class, 'season_id');
    }
}
