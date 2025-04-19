<?php

namespace App\Enums;

enum Roles: int {
    case Superadmin = 1;
    case Seller = 2;
    case Buyer = 3;

    public function displayName(): string {
        return match ($this) {
            self::Superadmin => 'Super Admin',
            self::Seller => 'Seller',
            self::Buyer => 'Buyer',
        };
    }
}
