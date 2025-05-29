<?php

use App\Http\Controllers\Profiles\PersonalInfoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Problems\ProblemController;




Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::prefix('problems')->group(function () {
    Route::get('/', [ProblemController::class, 'index'])->name('problems.index');               // list all problems
    Route::get('/create', [ProblemController::class, 'create'])->name('problems.create');       // form to add new problem
    Route::post('/', [ProblemController::class, 'store'])->name('problems.store');              // handle form submission
    Route::get('/{problem_handle}', [ProblemController::class, 'show'])->name('problems.show');        // show problem details
});



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    // Profile routes
    Route::get('profile', function () {
        $user = \Illuminate\Support\Facades\Auth::user();

        $statistics = [
            'solvedProblems' => 42, // This would come from your problems table
            'lastActiveDay' => $user->previous_active_at ? formatLastActive($user->previous_active_at) : 'Never',
            'streakDays' => $user->current_streak ?? 0,
            'maxStreakDays' => $user->max_streak ?? 0,
            'technicalScore' => 820, // This would be calculated based on your scoring system
            'socialScore' => 450, // This would be calculated based on your scoring system
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
                'description' => 'Maintained a 7-day streak',
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
require __DIR__.'/users.php';
