<?php

namespace App\Enums;

enum OrderStatus: int {
    case Pending = 1;
    case Approved = 2;
    case Declined = 3;

    public function displayName(): string {
        return match ($this) {
            self::Pending => 'Pending',
            self::Approved => 'Approved',
            self::Declined => 'Declined',
        };
    }
}
