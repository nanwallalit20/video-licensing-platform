<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Writer extends Model {
    use HasFactory;

    protected $table = 'writers';

    protected $fillable = [
        'name',
    ];

    public function getTitles() {
        return $this->belongsToMany(Title::class, 'map_writer_titles', 'writer_id', 'title_id');
    }
}
