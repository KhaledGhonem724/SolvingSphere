<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Profiles\PersonalInfoController;
use App\Http\Controllers\Containers\SheetController;
use App\Http\Controllers\HackerEarthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;    
use Inertia\Inertia;
use App\Http\Controllers\Blogs\BlogController;
use App\Http\Controllers\Blogs\CommentController;
use App\Models\Blog;



Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    }
    )->name('dashboard');

    // Profile routes
    Route::get('profile', function () {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Calculate social score based on blog interactions
        $blogs = Blog::where('owner_id', $user->user_handle)->get();

        // Calculate social score components
        $totalBlogs = $blogs->count();
        $totalBlogScore = $blogs->sum('score');
        $totalLikes = $blogs->sum(function ($blog) {
            return $blog->reactions()->where('value', 1)->count();
        });
        $totalComments = $blogs->sum(function ($blog) {
            return $blog->comments()->count();
        });


        $statistics = [
            'solvedProblems' => 12, // This would come from your problems table
            'lastActiveDay' => $user->previous_active_at ? formatLastActive($user->previous_active_at) : 'Never',
            'streakDays' => $user->current_streak ?? 0,
            'maxStreakDays' => $user->max_streak ?? 0,
            'technicalScore' => 80, // This would be calculated based on your scoring system
            'totalBlogScore' => $totalBlogScore, // Total score of all user's blogs
        ];

        $badges = [
            [
                'id' => 1,
                'name' => 'Problem Solver',
                'description' => 'Solved 10+ problems',
            ],
            [
                'id' => 2,
                'name' => 'Consistency King',
                'description' => 'Maintained a 3-day streak',
            ],
            [
                'id' => 3,
                'name' => 'Rising Star',
                'description' => 'Top performer this week',
            ],
        ];


        return Inertia::render('profile', [
            'statistics' => $statistics,
            'badges' => $badges,
            'socialLinks' => [
                'linkedin' => $user->linkedin_url,
                'github' => $user->github_url,
                'portfolio' => $user->portfolio_url,
            ],

        ]);
    })->name('profile');

    // Personal info settings routes
    Route::get('settings/personal-info', [PersonalInfoController::class, 'edit'])
        ->name('settings.personal-info');

    Route::patch('settings/personal-info', [PersonalInfoController::class, 'update'])
        ->name('settings.personal-info.update');

    // HackerEarth integration routes
    Route::prefix('api/hackerearth')->group(function () {
        Route::post('connect', [HackerEarthController::class, 'connect'])->name('hackerearth.connect');
        Route::post('refresh', [HackerEarthController::class, 'refresh'])->name('hackerearth.refresh');
        Route::delete('disconnect', [HackerEarthController::class, 'disconnect'])->name('hackerearth.disconnect');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

function formatLastActive($dateTime)
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



require __DIR__.'/admins.php';
require __DIR__.'/blogs.php';
require __DIR__.'/containers.php';
require __DIR__.'/groups.php';
require __DIR__.'/problems.php';
require __DIR__.'/submissions.php';
require __DIR__.'/home.php';

