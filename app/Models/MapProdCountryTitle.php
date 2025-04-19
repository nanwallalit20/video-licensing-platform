<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapProdCountryTitle extends Model {
    use HasFactory;

    protected $table = 'map_prod_country_titles';

    protected $fillable = [
        'title_id',
        'country_id'
    ];

    public function getCountry() {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
