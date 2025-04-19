<?php

namespace App\Enums;

enum RevenuePlanStatus: int {
    case Pending = 1;
    case InReview = 2;
    case Final = 3;

    public function displayName(): string {
        return match ($this) {
            self::Pending => 'Pending',
            self::InReview => 'In Review',
            self::Final => 'Approved'
        };
    }
}
