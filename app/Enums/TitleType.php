<?php

namespace App\Enums;

enum TitleType: int {
    case Series = 1;
    case Movie = 2;

    public function displayName(): string {
        return match ($this) {
            self::Series => 'TV Series',
            self::Movie => 'Movie',
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
}
