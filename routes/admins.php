<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Middleware\CheckAuthority;
use App\Http\Controllers\Admins\ReportController;
use App\Http\Controllers\Admins\TaskController;
use App\Http\Controllers\Admins\RoleController;
use App\Http\Controllers\Admins\AdminUserController;

use App\Http\Controllers\Blogs\BlogController;
// rest of the other controllers

Route::get('/reports/create', [ReportController::class, 'create'])->name('public.reports.create');
Route::post('/reports/create', [ReportController::class, 'store'])->name('public.reports.store');
// For regular users

// For manage_tasks authority 
Route::prefix('staff')->name('staff.')->middleware(['auth', 'can_authority:manage_tasks'])
   ->group(function () {

   // Reports
   Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
   Route::get('reports/{report_id}', [ReportController::class, 'show'])->name('reports.show');
   Route::get('reports/{report_id}/create-task', [ReportController::class, 'createTask'])->name('reports.create_task');
   Route::post('reports/{report_id}/mark-reviewed', [ReportController::class, 'markReviewed'])->name('reports.show.mark_reviewed');
   Route::post('reports/{report_id}/mark-ignored', [ReportController::class, 'markIgnored'])->name('reports.show.mark_ignored');


   Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
   Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create');
   Route::post('tasks/create', [TaskController::class, 'store'])->name('tasks.store');

   // Edit and Update Routes (accessible only to managers with 'manage_tasks' authority)
   Route::get('tasks/{task_id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
   Route::put('tasks/{task_id}/edit', [TaskController::class, 'update'])->name('tasks.update');

   Route::delete('tasks/{task_id}/delete', [TaskController::class, 'destroy'])->name('tasks.destroy');

});

Route::prefix('staff')->name('staff.')->middleware(['auth', 'can_authority:basic_admin'])
   ->group(function () {

   Route::get('tasks/{task_id}', [TaskController::class, 'show'])->name('tasks.show');
   Route::post('tasks/{task_id}/update-status/{status}', [TaskController::class, 'updateStatus'])->name('tasks.update_status');
   Route::post('tasks/{task_id}/add-note', [TaskController::class, 'addAdminNote'])->name('tasks.add_admin_note');
   Route::get('authorities/{authority}/tasks', [TaskController::class, 'byAuthority'])->name('tasks.by_authority');

});



// blog authority
Route::middleware(['auth', 'can_authority:manage_blogs'])->prefix('staff')
   ->name('staff.')
   ->group(function () {

   Route::get('blogs/manage', [BlogController::class, 'manage'])->name('blogs.manage');
   Route::patch('blogs/{blog}/toggle-status', [BlogController::class, 'toggleStatus'])->name('blogs.toggle_status');
});

// Super Admin
Route::prefix('staff')->name('staff.')->middleware(['auth', 'can_authority:manage_roles'])->group(function () {
   Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
   Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
   Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
   Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
   Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
   Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

   Route::get('/admins', [AdminUserController::class, 'index'])->name('admins.index');
   Route::get('/admins/create', [AdminUserController::class, 'create'])->name('admins.create');
   Route::post('/admins', [AdminUserController::class, 'store'])->name('admins.store');
   Route::delete('/admins/{user}', [AdminUserController::class, 'destroy'])->name('admins.destroy');
   // List users that can be promoted
   Route::get('/admins/promote', [AdminUserController::class, 'promoteList'])->name('admins.promote');
   // Assign role to promote a user
   Route::post('/admins/promote/{user}', [AdminUserController::class, 'promote'])->name('admins.promote_user');

});
