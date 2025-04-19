<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapBuyerGenre extends Model {
    use HasFactory;

    protected $table = 'map_buyer_genres';
    protected $fillable = [
        'buyer_id',
        'genre_id'

    ];
}
