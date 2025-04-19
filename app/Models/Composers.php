<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composers extends Model {
    use HasFactory;

    protected $table = 'composers';

    protected $fillable = [
        'name'
    ];

    public function getTitles() {
        return $this->belongsToMany(Title::class, 'map_composer_titles', 'composer_id', 'title_id');
    }
}
