<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderMeta extends Model {
    protected $table = 'order_meta';

    protected $fillable = [
        'order_id',
        'title_id',
        'season_id'
    ];

    public function getOrder() {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function getTitle() {
        return $this->belongsTo(Title::class, 'title_id', 'id');
    }

    public function getSeason() {
        return $this->belongsTo(Season::class, 'season_id', 'id');
    }
}
