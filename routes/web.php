<?php

use App\Http\Controllers\Profiles\PersonalInfoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Problems\ProblemController;
use App\Http\Controllers\Groups\GroupController;
use App\Http\Controllers\Groups\GroupMaterialController;
use App\Http\Controllers\Groups\GroupChatController;
use App\Http\Controllers\Groups\GroupMemberController;
use App\Http\Controllers\Groups\GroupJoinRequestController;
use App\Http\Controllers\Groups\GroupMessageController;

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
            'lastActiveDay' => $user->previous_active_at ? app('formatLastActive')($user->previous_active_at) : 'Never',
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

    // Group Routes
    Route::prefix('groups')->name('groups.')->group(function () {
        Route::get('/', [GroupController::class, 'index'])->name('index');
        Route::get('/create', [GroupController::class, 'create'])->name('create');
        Route::post('/', [GroupController::class, 'store'])->name('store');
        Route::get('/{group}', [GroupController::class, 'show'])->name('show');
        Route::get('/{group}/edit', [GroupController::class, 'edit'])->name('edit');
        Route::put('/{group}', [GroupController::class, 'update'])->name('update');
        Route::delete('/{group}', [GroupController::class, 'destroy'])->name('destroy');

        // Group Materials
        Route::get('/{group}/materials', [GroupMaterialController::class, 'index'])->name('materials.index');
        Route::get('/{group}/materials/create', [GroupMaterialController::class, 'create'])->name('materials.create');
        Route::post('/{group}/materials', [GroupMaterialController::class, 'store'])->name('materials.store');
        
        // Group Messages
        Route::get('/{group}/chat', [GroupChatController::class, 'index'])->name('chat.index');
        Route::post('/{group}/chat', [GroupChatController::class, 'store'])->name('chat.store');
        
        // Group Members
        Route::post('/{group}/members', [GroupMemberController::class, 'store'])->name('members.store');
        Route::delete('/{group}/members/{user}', [GroupMemberController::class, 'destroy'])->name('members.destroy');
        Route::put('/{group}/members/{user}/role', [GroupMemberController::class, 'updateRole'])->name('members.updateRole');
        
        // Join Requests
        Route::post('/{group}/join', [GroupJoinRequestController::class, 'store'])->name('join.store');
        Route::put('/{group}/join/{request}', [GroupJoinRequestController::class, 'update'])->name('join.update');
    });
});

// مسارات المجموعات
Route::middleware(['auth'])->group(function () {
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::put('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::post('/groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');
    Route::put('/groups/{group}/members/{user}', [GroupController::class, 'updateMember'])->name('groups.members.update');
    Route::delete('/groups/{group}/members/{user}', [GroupController::class, 'removeMember'])->name('groups.members.remove');

    // مسارات الرسائل
    Route::post('/groups/{group}/messages', [GroupMessageController::class, 'store'])->name('groups.messages.store');
    Route::post('/groups/{group}/messages/{message}/read', [GroupMessageController::class, 'markAsRead'])->name('groups.messages.read');
    Route::delete('/groups/{group}/messages/{message}', [GroupMessageController::class, 'destroy'])->name('groups.messages.destroy');

    // مسارات طلبات الانضمام
    Route::post('/groups/{group}/join-requests', [GroupJoinRequestController::class, 'store'])->name('groups.join-requests.store');
    Route::post('/groups/{group}/join-requests/{request}/approve', [GroupJoinRequestController::class, 'approve'])->name('groups.join-requests.approve');
    Route::post('/groups/{group}/join-requests/{request}/reject', [GroupJoinRequestController::class, 'reject'])->name('groups.join-requests.reject');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/admins.php';
require __DIR__.'/blogs.php';
require __DIR__.'/containers.php';
require __DIR__.'/problems.php';
//require __DIR__.'/users.php';
