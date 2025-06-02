<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Problems\ProblemController;
use App\Http\Controllers\Submissions\SubmissionController;

Route::middleware(['auth'])->prefix('submissions')->group(function () {
    Route::get('/', [SubmissionController::class, 'index'])->name('submissions.index');               // List all submissions
    Route::get('/create', [SubmissionController::class, 'general_create'])->name('submissions.general.create');               // List all submissions
    Route::post('/create', [SubmissionController::class, 'general_store'])->name('submissions.general.store');
    Route::get('/{id}', [SubmissionController::class, 'show'])->name('submissions.show');     // Show submission details
    
    // not active yet
    Route::post('/{id}/refresh', [SubmissionController::class, 'refresh'])->name('submissions.refresh');
});
