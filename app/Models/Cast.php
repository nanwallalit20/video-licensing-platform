<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cast extends Model {
    use HasFactory;

    protected $table = 'casts';

    protected $fillable = [
        'title_id',
        'actor_id',
        'character'
    ];

    public function getActor() {
        $this->hasOne(Actor::class);
    }

}
