<?php

namespace App\Models;

use App\Enums\TitleCompletion;
use App\Enums\TitleStatus;
use App\Enums\TitleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model {
    use HasFactory;

    protected $table = 'titles';

    protected $fillable = [
        'user_id',
        'uuid',
        'slug',
        'name',
        'status',
        'reason',
        'type',
        'isSubmitted'
    ];

    protected $casts = [
        'type' => TitleType::class, // Automatically cast to enum
        'status' => TitleStatus::class,
        'isSubmitted' => TitleCompletion::class
    ];


    public function getMeta() {
        return $this->hasOne(TitleMeta::class);
    }

    public function getSeasons() {
        return $this->hasMany(Season::class, 'title_id', 'id');
    }

    public function getMovieMeta() {
        return $this->hasOne(MovieMeta::class);
    }

    public function getGenres() {
        return $this->belongsToMany(Genre::class, 'map_genre_titles', 'title_id', 'genre_id');
    }

    public function getKeywords() {
        return $this->belongsToMany(Keywords::class, MapKeywordTitle::class, 'title_id', 'keyword_id');
    }

    public function getRatings() {
        return $this->belongsToMany(OfficialRating::class, 'map_official_rating_titles', 'title_id', 'official_rating_id');
    }

    public function getProductionCountries() {
        return $this->belongsToMany(Country::class, MapProdCountryTitle::class, 'title_id', 'country_id');
    }

    public function getLicenceCountry() {
        return $this->belongsToMany(Country::class, MapLicenceCountryTitle::class, 'title_id', 'country_id');
    }

    public function getOfficialRatings() {
        return $this->belongsToMany(OfficialRating::class, 'map_official_rating_titles', 'title_id', 'official_rating_id');
    }

    public function getCatalog() {
        return $this->hasOne(Catalog::class);
    }

    public function getActors() {
        return $this->belongsToMany(Actor::class, 'casts', 'title_id', 'actor_id')->withPivot('character');
    }

    public function getDirectors() {
        return $this->belongsToMany(Director::class, 'map_director_titles', 'title_id', 'director_id');
    }

    public function getProducers() {
        return $this->belongsToMany(Producer::class, 'map_producer_titles', 'title_id', 'producer_id');
    }

    public function getWriters() {
        return $this->belongsToMany(Writer::class, 'map_writer_titles', 'title_id', 'writer_id');
    }

    public function getComposers() {
        return $this->belongsToMany(Composers::class, 'map_composer_titles', 'title_id', 'composer_id');
    }

    public function getTags() {
        return $this->belongsToMany(Tag::class, 'map_tag_titles', 'title_id', 'tag_id');
    }

    public function getFestivals() {
        return $this->belongsToMany(Festival::class, 'map_festival_titles', 'title_id', 'festival_id')->withPivot('type');
    }

    public function getContacts() {
        return $this->belongsToMany(Contact::class, 'map_contact_titles', 'title_id', 'contact_id');
    }


    public function getUser() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getAdvisories() {
        return $this->hasManyThrough(Advisory::class, MapAdvisoryTitle::class, 'title_id', 'id', 'id', 'advisory_id');
    }

    public function getRevenuePlan() {
        return $this->hasOne(RevenuePlan::class, 'title_id', 'id');
    }

    public function getGenreMappings() {
        return $this->hasMany(MapGenreTitle::class, 'title_id');
    }

    public function getContactMappings() {
        return $this->hasMany(MapContactTitle::class, 'title_id');
    }

    public function getKeywordMappings() {
        return $this->hasMany(MapKeywordTitle::class, 'title_id');
    }

    public function getAdvisoryMappings() {
        return $this->hasMany(MapAdvisoryTitle::class, 'title_id');
    }

    public function getFestivalMappings() {
        return $this->hasMany(MapFestivalTitle::class, 'title_id');
    }

    public function getDirectorMappings() {
        return $this->hasMany(MapDirectorTitle::class, 'title_id');
    }

    public function getProducerMappings() {
        return $this->hasMany(MapProducerTitle::class, 'title_id');
    }

    public function getWriterMappings() {
        return $this->hasMany(MapWriterTitle::class, 'title_id');
    }

    public function getComposerMappings() {
        return $this->hasMany(MapComposerTitle::class, 'title_id');
    }

    public function getTagMappings() {
        return $this->hasMany(MapTagTitle::class, 'title_id');
    }

    public function getOfficialRatingMappings() {
        return $this->hasMany(MapOfficialRatingTitle::class, 'title_id');
    }

    public function getProductionCountriesMappings() {
        return $this->hasMany(MapProdCountryTitle::class, 'title_id');
    }

    public function getLicenceCountryMapping() {
        return $this->hasMany(MapLicenceCountryTitle::class, 'title_id');
    }

    public function getTitleMediaMapping() {
        return $this->hasMany(MapTitleMedia::class, 'title_id', 'id');
    }

    // Relation with Genres through MapGenreTitles
    public function getGenresThroughMapGenreTitles() {
        return $this->hasManyThrough(
            Genre::class,
            MapGenreTitle::class,
            'title_id',   // Foreign key on map_genre_titles table
            'id',         // Foreign key on genres table
            'id',         // Local key on titles table
            'genre_id'    // Local key on map_genre_titles table
        );
    }

    public function getMediaThroughMovie() {
        return $this->hasManyThrough(
            Media::class,
            MapTitleMedia::class,
            'title_id',   // Foreign key on the `movies` table
            'id',         // Foreign key on the `media` table
            'id',         // Local key on the `titles` table
            'media_id'    // Local key on the `movies` table
        );
    }
}
