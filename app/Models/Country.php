<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model {
    use HasFactory;

    protected $table = 'countries';

    public function getProductionTitles() {
        return $this->belongsToMany(Title::class, 'map_prod_country_titles', 'country_id', 'title_id');
    }
}
