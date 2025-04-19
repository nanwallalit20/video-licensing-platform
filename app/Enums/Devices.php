<?php

namespace App\Enums;

enum Devices: int {
    case Mobile = 1;
    case Desktop = 2;
    case SmartTv = 3;
    case Tablet = 4;


    public function displayName(): string {
        return match ($this) {
            self::Mobile => 'Mobile',
            self::Desktop => 'Desktop',
            self::SmartTv => 'Smart TV',
            self::Tablet => 'Tablet',
        };
    }
}
