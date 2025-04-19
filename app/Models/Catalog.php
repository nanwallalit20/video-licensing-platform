<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model {
    use HasFactory;

    protected $table = 'catalogs';

    protected $fillable = [
        'title_id',
        'imdb_url',
        'tmdb_url',
        'vendor_id',
        'production_company',
        'copyright_line',
        'notes',
        'public_domain',
    ];

    public function getTitle() {
        return $this->belongsTo(Title::class);
    }
}
