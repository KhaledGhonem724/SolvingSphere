<?php

namespace App\Enums;

enum VisibilityStatus: string
{
    case Visible = 'visible';
    case Hidden = 'hidden';

    public function label(): string
    {
        return match ($this) {
            self::Visible => 'Visible',
            self::Hidden => 'Hidden',
        };
    }


}
