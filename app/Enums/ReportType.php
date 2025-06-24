<?php

namespace App\Enums;

enum ReportType: string
{
    case SCIENTIFIC = 'scientific';
    case ETHICAL = 'ethical';
    case TECHNICAL = 'technical';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::SCIENTIFIC => 'Scientific',
            self::ETHICAL    => 'Ethical',
            self::TECHNICAL  => 'Technical',
            self::OTHER      => 'Other',
        };
    }
    public static function casesEnumValues(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }
}
