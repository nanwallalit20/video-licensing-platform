<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapTagTitle extends Model {
    use HasFactory;

    protected $table = 'map_tag_titles';

    protected $fillable = [
        'title_id',
        'tag_id'
    ];

    public function getTag() {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
}
