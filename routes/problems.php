<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Problems\ProblemController;
use App\Http\Controllers\Submissions\SubmissionController;

Route::prefix('problems')->group(function () {
    Route::get('/', [ProblemController::class, 'index'])->name('problems.index');               // list all problems
    Route::get('/create', [ProblemController::class, 'create'])->name('problems.create');       // form to add new problem
    Route::post('/', [ProblemController::class, 'store'])->name('problems.store');              // handle form submission
    Route::get('/{problem_handle}', [ProblemController::class, 'show'])->name('problems.show');        // show problem details
});

Route::prefix('submissions')->group(function () {
    Route::get('/', [SubmissionController::class, 'index'])->name('submissions.index');               // List all submissions
    Route::get('/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');     // Show submission details
});

Route::get('/problems/{problem_handle}/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
Route::post('/problems/{problem_handle}/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
Route::get('/problems/{problem_handle}/submissions/create', [SubmissionController::class, 'create'])->name('submissions.create');

/*
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/problems/{problem_handle}/submissions/create', [SubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/problems/{problem_handle}/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
});
*/