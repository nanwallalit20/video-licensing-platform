<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapComposerTitle extends Model {
    use HasFactory;

    protected $table = 'map_composer_titles';

    protected $fillable = [
        'title_id',
        'composer_id'
    ];

    public function getComposer() {
        return $this->belongsTo(Composers::class, 'composer_id');
    }

}
