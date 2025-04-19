<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapTitleMedia extends Model {
    use HasFactory;

    protected $table = 'map_title_media';
    protected $fillable = [
        'title_id',
        'media_id',
        'text',
    ];

    public function getMedia() {
        return $this->belongsTo(Media::class, 'media_id', 'id');
    }
}

