<?php

namespace App\Enums;

enum MediaTypes: int {
    case Trailer = 1;
    case MainVideo = 2;
    case LegalDoc = 3;
    case Caption = 4;
    case AdditionalLanguage = 5;
    case Image = 6;
    case Image_Landscape = 7;

    public function displayName(): string {
        return match ($this) {
            self::Trailer => 'Trailer',
            self::MainVideo => 'Main Video',
            self::LegalDoc => 'Legal Doc',
            self::Caption => 'Subtitles',
            self::Image => 'Image Portrait',
            self::AdditionalLanguage => 'Dubbed Language',
            self::Image_Landscape => 'Image Landscape',
        };
    }

    /**
     * Return the directory path for the media type
     * @return string
     */

    public function directoryPath(): string {
        return match ($this) {
            self::Trailer => 'trailer',
            self::MainVideo => 'main_video',
            self::LegalDoc => 'legal_doc',
            self::Caption => 'caption',
            self::Image => 'image',
            self::AdditionalLanguage => 'additional_language',
            self::Image_Landscape => 'image_landscape',
        };
    }

    /**
     * Get all enum cases as an associative array with names as keys and values as values.
     *
     * @return array
     */
    public static function asArray(): array {
        $result = [];
        foreach (self::cases() as $case) {
            $result[$case->name] = $case->value;
        }
        return $result;
    }

    /**
     * Get movie movie path to upload movie media files
     * @param string $title UUID of the movie title from the database
     * @param string $mediaType // 'trailer', 'main_video', 'legal_doc', 'caption', 'additional_language', 'image'
     * @return string
     */

    public static function moviePath($title, $mediaType) {
        return "movie/{$title}/{$mediaType}";
    }

    /**
     * Get movie movie path to upload movie media files
     * @param string $title UUID of the title from the database
     * @param string $season UUID of the season from the database
     * @param string $mediaType 'trailer', 'image', 'legal_doc'
     * @return string
     */
    public static function seasonPath($title, $mediaType, $season = null) {
        return $season ? "season/{$title}/{$season}/{$mediaType}" : "season/{$title}/{$mediaType}";
    }

    /**
     * Get movie movie path to upload movie media files
     * @param string $title UUID of the title from the database
     * @param string $season UUID of the season from the database
     * @param string $episode UUID of the season episode from the database
     * @param string $mediaType 'main_video', 'caption', 'additional_language', 'image'
     * @return string
     */

    public static function episodePath($title, $season, $episode, $mediaType) {
        return "season/{$title}/{$season}/{$episode}/{$mediaType}";
    }

}
