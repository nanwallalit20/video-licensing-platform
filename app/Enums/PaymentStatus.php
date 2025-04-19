<?php

namespace App\Enums;

enum PaymentStatus: int {
    case Pending = 1;
    case Paid = 2;
    case Failed = 3;
    case Refunded = 4;

    public function displayName(): string {
        return match ($this) {
            self::Pending => 'Pending',
            self::Paid => 'Paid',
            self::Failed => 'Failed',
            self::Refunded => 'Refunded',
        };
    }
}
