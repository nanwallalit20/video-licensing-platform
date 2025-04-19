<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapBuyerTitle extends Model {
    use HasFactory;

    protected $table = 'map_buyer_title';

    protected $fillable = [
        'title_id',
        'buyer_id',
    ];

    public function getTitle() {
        return $this->belongsTo(Title::class, 'title_id', 'id');
    }

    public function getUser() {
        return $this->belongsTo(User::class, 'buyer_id', 'id');
    }

}
