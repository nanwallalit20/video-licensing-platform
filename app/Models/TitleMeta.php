<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitleMeta extends Model {
    use HasFactory;

    protected $table = 'title_metas';

    protected $fillable = [
        'title_id',
        'synopsis',
        'sales_pitch'
    ];

    public function getTitle() {
        return $this->belongsTo(Title::class);
    }
}
