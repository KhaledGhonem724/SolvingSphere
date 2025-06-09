<?php

namespace App\Helpers;

class DateHelper
{
    public static function formatLastActive($dateTime)
    {
        $now = now();
        $date = $dateTime->copy();

        if ($date->isToday()) {
            return 'Today at ' . $date->format('g:i A');
        }

        if ($date->isYesterday()) {
            return 'Yesterday at ' . $date->format('g:i A');
        }

        if ($date->isSameWeek($now)) {
            return $date->format('l') . ' at ' . $date->format('g:i A');
        }

        return $date->format('M j, Y') . ' at ' . $date->format('g:i A');
    }
} 