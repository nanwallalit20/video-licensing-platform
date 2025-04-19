<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docusign extends Model {
    use HasFactory;

    protected $table = 'docusign';

    protected $fillable = [
        'access_token',
        'token_type',
        'refresh_token',
        'expires_in'
    ];

}
