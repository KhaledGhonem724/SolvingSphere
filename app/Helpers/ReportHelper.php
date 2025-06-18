<?php

namespace App\Helpers;

class ReportHelper
{
    public static function resolveReportableClass(string $type): ?string
    {
        return match (strtolower($type)) {
            'blog' => \App\Models\Blog::class,
            'comment' => \App\Models\Comment::class,
            'problem' => \App\Models\Problem::class,
            'user' => \App\Models\User::class,
            default => null,
        };
        // wait for ahmed and anas
        /*
            'group' => \App\Models\Group::class,
            'sheet' => \App\Models\Sheet::class,
            'topic' => \App\Models\Topic::class,
            'roadmap' => \App\Models\Roadmap::class,
        */
    }

    public static function resolveReportableType(string $class): ?string
    {
        return match ($class) {
            \App\Models\Blog::class => 'blog',
            \App\Models\Comment::class => 'comment',
            \App\Models\Problem::class => 'problem',
            \App\Models\User::class => 'user',
            default => null,
        };
        // wait for ahmed and anas
        /*
            \App\Models\Group::class => 'group',
            \App\Models\Sheet::class => 'sheet',
            \App\Models\Topic::class => 'topic',
            \App\Models\Roadmap::class => 'roadmap' ,
        */
    }
}
