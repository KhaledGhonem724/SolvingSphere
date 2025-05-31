<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Blogs\BlogController;
use App\Http\Controllers\Blogs\CommentController;

Route::resource('blogs', BlogController::class);
/*
Route::get('blogs', [BlogController::class, 'index']);
Route::get('blogs/create', [BlogController::class, 'create']);
Route::post('blogs', [BlogController::class, 'store']);
Route::get('blogs/{blog}', [BlogController::class, 'show']);
Route::get('blogs/{blog}/edit', [BlogController::class, 'edit']);
Route::put('blogs/{blog}', [BlogController::class, 'update']);
Route::delete('blogs/{blog}', [BlogController::class, 'destroy']);

*/
Route::post('blogs/{blog}/comments', [CommentController::class, 'store'])->name('comments.store');

Route::resource('comments', CommentController::class)->only(['edit', 'update', 'destroy']);