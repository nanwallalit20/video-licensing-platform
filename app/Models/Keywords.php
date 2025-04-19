<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keywords extends Model {
    use HasFactory;

    protected $table = 'keywords';

    protected $fillable = [
        'name'
    ];

    public function getTitles() {
        return $this->belongsToMany(Title::class, 'map_keyword_titles', 'keyword_id', 'title_id');
    }
}
