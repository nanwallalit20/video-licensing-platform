<?php

namespace App\Models;

use App\Enums\MediaTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model {
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'file_path', 'file_name', 'file_type',
    ];

    protected $casts = [
        'file_type' => MediaTypes::class
    ];

}
