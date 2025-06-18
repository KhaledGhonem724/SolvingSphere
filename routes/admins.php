<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Middleware\CheckAuthority;
use App\Http\Controllers\Admins\ReportController;
use App\Http\Controllers\Admins\TaskController;

Route::get('/reports/create', [ReportController::class, 'create'])->name('public.reports.create');
Route::post('/reports/create', [ReportController::class, 'store'])->name('public.reports.store');
// For regular users

// For manage_tasks authority 
Route::prefix('staff')
   ->name('staff.')
   ->middleware(['auth', 'can_authority:manage_tasks'])
   ->group(function () {

   // Reports
   Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
   Route::get('reports/{report_id}', [ReportController::class, 'show'])->name('reports.show');
   Route::get('reports/{report_id}/create-task', [ReportController::class, 'createTask'])->name('reports.create_task');
   Route::post('reports/{report_id}/mark-reviewed', [ReportController::class, 'markReviewed'])->name('reports.show.mark_reviewed');
   Route::post('reports/{report_id}/mark-ignored', [ReportController::class, 'markIgnored'])->name('reports.show.mark_ignored');

   Route::post('tasks/create', [TaskController::class, 'store'])->name('tasks.store');

   // Edit and Update Routes (accessible only to managers with 'manage_tasks' authority)
   Route::get('tasks/{task_id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
   Route::put('tasks/{task_id}/edit', [TaskController::class, 'update'])->name('tasks.update');

});

Route::prefix('staff')
   ->name('staff.')
   ->middleware(['auth', 'can_authority:basic_admin'])
   ->group(function () {

   Route::get('tasks/{task_id}', [TaskController::class, 'show'])->name('tasks.show');
   Route::post('tasks/{task_id}/update-status/{status}', [TaskController::class, 'updateStatus'])->name('tasks.update_status');
   Route::post('tasks/{task_id}/add-note', [TaskController::class, 'addAdminNote'])->name('tasks.add_admin_note');


});