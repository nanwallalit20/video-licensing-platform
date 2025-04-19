<?php

namespace App\Enums;

enum TitleCompletion: int {
    case Completed = 1;
    case Pending = 2;

    public function displayName(): string {
        return match ($this) {
            self::Completed => 'Submitted',
            self::Pending => 'Pending',
        };
    }
}
