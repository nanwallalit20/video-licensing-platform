<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapLicenceCountryTitle extends Model {
    use HasFactory;

    protected $table = 'map_licence_country_titles';

    protected $fillable = [
        'title_id',
        'country_id'
    ];
}
