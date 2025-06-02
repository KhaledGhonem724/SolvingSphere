<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Problems\ProblemController;
use App\Http\Controllers\Submissions\SubmissionController;

Route::prefix('problems')->group(function () {
    Route::get('/', [ProblemController::class, 'index'])->name('problems.index');               // list all problems
    Route::post('/create', [ProblemController::class, 'store'])->middleware('auth')->name('problems.store');              // handle form submission
    Route::get('/create', [ProblemController::class, 'create'])->middleware('auth')->name('problems.create');       // form to add new problem
    Route::get('/{problem_handle}', [ProblemController::class, 'show'])->name('problems.show');        // show problem details
    // not used yet
    Route::get('/{problem_handle}/submissions', [SubmissionController::class, 'index_by_problem'])->middleware('auth')->name('problem.submissions');
    Route::post('/{problem_handle}/submissions/create', [SubmissionController::class, 'store'])->middleware('auth')->name('submissions.store');
    Route::get('/{problem_handle}/submissions/create', [SubmissionController::class, 'create'])->middleware('auth')->name('submissions.create');
});
