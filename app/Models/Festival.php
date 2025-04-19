<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Festival extends Model {
    use HasFactory;

    protected $table = 'festivals';

    protected $fillable = [
        'name',
        'slug'
    ];

    public function getTitles() {
        return $this->belongsToMany(Title::class, 'map_festival_titles', 'festival_id', 'title_id')->withPivot('type');
    }
}
