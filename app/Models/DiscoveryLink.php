<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscoveryLink extends Model {
    use HasFactory;

    protected $table = 'discovery_links';

    protected $fillable = [
        'url',
    ];
}
