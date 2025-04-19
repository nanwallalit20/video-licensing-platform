<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapFestivalTitle extends Model {
    use HasFactory;

    protected $table = 'map_festival_titles';

    protected $fillable = [
        'title_id',
        'festival_id',
        'type'
    ];

    public function getProducer() {
        return $this->belongsTo(Festival::class, 'festival_id');
    }

}
