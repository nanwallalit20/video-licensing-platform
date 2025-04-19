<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producer extends Model {
    use HasFactory;

    protected $table = 'producers';

    protected $fillable = [
        'name'
    ];

    public function getTitles() {
        return $this->belongsToMany(Title::class, 'map_producer_titles', 'producer_id', 'title_id');
    }
}
