<?php

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
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/admins.php';
require __DIR__.'/blogs.php';
require __DIR__.'/containers.php';
require __DIR__.'/groups.php';
require __DIR__.'/problems.php';
require __DIR__.'/users.php';
