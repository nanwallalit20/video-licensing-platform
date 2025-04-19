<?php

namespace App\Enums;

enum BuyerStatus: int {
    case Accepted = 1;
    case Pending = 2;
    case Rejected = 3;


    public function displayName(): string {
        return match ($this) {
            self::Rejected => 'Declined',
            self::Accepted => 'Approved',
            self::Pending => 'Pending',
        };
    }
}
