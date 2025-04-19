<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model {
    use HasFactory;

    protected $table = 'actors';

    protected $fillable = [
        'name',
        'gender'
    ];

    public function getTitles() {
        return $this->belongsToMany(Title::class, 'casts', 'actor_id', 'title_id')->withPivot('character');
    }
}
