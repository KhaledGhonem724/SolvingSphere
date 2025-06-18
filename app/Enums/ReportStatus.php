<?php

namespace App\Enums;

enum ReportStatus: string
{
    case NEW          = 'new';
    case REVIEWED     = 'reviewed';
    case IGNORED      = 'ignored';

    public function label(): string
    {
        return match ($this) {
            self::NEW          => 'New',
            self::REVIEWED     => 'Reviewed',
            self::IGNORED      => 'Ignored',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::REVIEWED, self::IGNORED]);
    }
}
