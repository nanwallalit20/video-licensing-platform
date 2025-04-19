<?php

namespace App\Enums;

enum AcquisitionPreference: int {
    case LICENSE = 1;
    case PURCHASE = 2;
    case REVENUE_SHARE = 3;
    case OTHER = 4;

    /**
     * Get the description for the rating.
     */
    public function description(): string {
        return match ($this) {
            self::LICENSE => 'Lincence',
            self::PURCHASE => 'Purchase',
            self::REVENUE_SHARE => 'Revenue Share',
            self::OTHER => 'Other',
        };
    }
}
