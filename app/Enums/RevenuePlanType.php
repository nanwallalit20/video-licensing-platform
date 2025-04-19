<?php

namespace App\Enums;

enum RevenuePlanType: int {
    case PayPerView = 1;
    case Avod = 2;
    case AvodBuyer = 3;

    public function displayName(): string {
        return match ($this) {
            self::PayPerView => 'Pay Per View',
            self::Avod => 'Avod',
            self::AvodBuyer => 'Avod Buyer',
        };
    }

    public function planInfo() {
        return match ($this) {
            self::PayPerView => [
                '- No Money Upfront',
                '- 15% of earning will go to Kalingo for Administrative and marketing',
                '- The remaining 85% will be 60% to Kalingo and 40% to Seller',
            ],
            self::Avod => [
                '- No Money Upfront',
                '- 15% of earning will go to Kalingo for Administrative and marketing',
                '- The remaining 85% will be 50% / 50%',
            ],
            self::AvodBuyer => [
                '- $5000.00 Upfront',
                '- 10% of earning will go to Kalingo for Administrative and marketing',
                '- The remaining 90% will be split 30% to Kalingo and 70% to Seller',
            ],
        };
    }


}
