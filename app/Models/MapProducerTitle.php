<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapProducerTitle extends Model {
    use HasFactory;

    protected $table = 'map_producer_titles';


    protected $fillable = [
        'title_id',
        'producer_id'
    ];

    public function getProducer() {
        return $this->belongsTo(Producer::class, 'producer_id');
    }

}
