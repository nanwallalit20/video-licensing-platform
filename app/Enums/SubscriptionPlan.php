<?php

namespace App\Enums;

enum SubscriptionPlan: int {
    case Free = 1;
    case Yearly = 2;
    case Monthly = 3;

    public function displayName(): string {
        return match ($this) {
            self::Free => 'Free',
            self::Yearly => 'Yearly',
            self::Monthly => 'Monthly',
        };
    }
}
