<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapWriterTitle extends Model {
    use HasFactory;

    protected $table = 'map_writer_titles';

    protected $fillable = [
        'title_id',
        'writer_id'
    ];

    public function getWriter() {
        return $this->belongsTo(Writer::class, 'writer_id');
    }

}
