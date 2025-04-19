<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasonMedia extends Model {
    use HasFactory;

    protected $table = 'season_media';

    protected $fillable = [
        'season_id',
        'media_id'
    ];

    public function getMedia() {
        return $this->belongsTo(Media::class, 'media_id', 'id');
    }
}
