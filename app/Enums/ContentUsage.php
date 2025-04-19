<?php

namespace App\Enums;

enum ContentUsage: int {
    case STREAMING_PLATFORM = 1;
    case BROADCAST_TV = 2;
    case THEATRICAL_RELEASE = 3;
    case OTHER = 4;

    /**
     * Get the description for the rating.
     */
    public function description(): string {
        return match ($this) {
            self::STREAMING_PLATFORM => 'Streaming Platform',
            self::BROADCAST_TV => 'Broadcast TV',
            self::THEATRICAL_RELEASE => 'Theatrical Release',
            self::OTHER => 'Other',
        };
    }

}
