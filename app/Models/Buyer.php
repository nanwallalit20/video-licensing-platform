<?php

namespace App\Models;

use App\Enums\AcquisitionPreference;
use App\Enums\BuyerStatus;
use App\Enums\ContentDuration;
use App\Enums\ContentUsage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model {
    use HasFactory;

    protected $table = 'buyers';
    protected $fillable = [
        'full_name',
        'company_name',
        'job_title',
        'whatsapp_number',
        'terms_and_conditons',
        'email',
        'phone_number',
        'content_type',
        'genre',
        'content_usage',
        'content_duration',
        'acquisition_preferences',
        'target_audience',
        'territories',
        'budget',
        'additional_details',
        'status',
    ];

    protected $casts = [
        'content_usage' => ContentUsage::class,
        'content_duration' => ContentDuration::class,
        'acquisition_preferences' => AcquisitionPreference::class,
        'status' => BuyerStatus::class,
    ];

    public function getGenres() {
        return $this->belongsToMany(Genre::class, 'map_buyer_genres', 'buyer_id', 'genre_id');
    }

    public function getContentTypes() {
        return $this->hasMany(BuyerContentType::class, 'buyer_id', 'id');
    }

}
