<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Director extends Model {
    use HasFactory;

    protected $table = 'directors';

    protected $fillable = [
        'name',
        'gender'
    ];

    public function getTitles() {
        return $this->belongsToMany(Title::class, 'map_director_titles', 'director_id', 'title_id');
    }

}
