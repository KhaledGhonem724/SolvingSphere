<?php

namespace App\Enums;

enum ReportStatus: string
{
    case NEW          = 'new';
    case ASSIGNED     = 'assigned';
    case UNDER_REVIEW = 'under_review';
    case RESOLVED     = 'resolved';
    case DROPPED      = 'dropped';

    public function label(): string
    {
        return match ($this) {
            self::NEW          => 'New',
            self::ASSIGNED     => 'Assigned',
            self::UNDER_REVIEW => 'Under Review',
            self::RESOLVED     => 'Resolved',
            self::DROPPED      => 'Dropped',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::RESOLVED, self::DROPPED]);
    }
}
