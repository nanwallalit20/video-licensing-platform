<?php

namespace App\Models;

use App\Enums\ContentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerContentType extends Model {
    use HasFactory;

    protected $table = 'buyer_content_type';
    protected $fillable = [
        'buyer_id',
        'content_type',

    ];
    protected $casts = [
        'content_type' => ContentType::class
    ];
}
