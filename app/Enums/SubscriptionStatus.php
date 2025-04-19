<?php

namespace App\Enums;

enum SubscriptionStatus: int {
    case SUBSCRIBED = 1;
    case NOT_SUBSCRIBED = 2;

    /**
     * Returns an array mapping enum values to display names.
     */
    public function displayName(): string {
        return match ($this) {
            self::SUBSCRIBED => 'Subscribed',
            self::NOT_SUBSCRIBED => 'Not Subscribed',
        };
    }
}
