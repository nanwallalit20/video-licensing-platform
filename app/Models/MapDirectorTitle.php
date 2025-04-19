<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapDirectorTitle extends Model {
    use HasFactory;

    protected $table = 'map_director_titles';

    protected $fillable = [
        'title_id',
        'director_id'
    ];

    public function getDirector() {
        return $this->belongsTo(Director::class, 'director_id');
    }

}
