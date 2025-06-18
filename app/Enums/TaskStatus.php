<?php
// app/Enums/TaskStatus.php
namespace App\Enums;

enum TaskStatus: string
{
    case Free = 'free';
    case Assigned = 'assigned';
    case Refused = 'refused';
    case Solved = 'solved';
    case Dismissed = 'dismissed';

    public function label(): string
    {
        return match ($this) {
            self::Free => 'Free',
            self::Assigned => 'Assigned',
            self::Refused => 'Refused',
            self::Solved => 'Solved',
            self::Dismissed => 'Dismissed',
        };
    }
    public static function casesEnumValues(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }

}
