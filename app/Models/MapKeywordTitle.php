<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapKeywordTitle extends Model {
    use HasFactory;

    protected $table = 'map_keyword_titles';

    protected $fillable = [
        'keyword_id',
        'title_id'
    ];

    public function getKeyword() {
        return $this->belongsTo(Keywords::class, 'keyword_id');
    }
}
