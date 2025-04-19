<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapAdvisoryTitle extends Model {
    use HasFactory;

    protected $table = 'map_advisory_titles';

    protected $fillable = [
        'advisory_id',
        'title_id'
    ];

    public function getAdvisory() {
        return $this->belongsTo(Advisory::class, 'advisory_id', 'id');
    }
}
