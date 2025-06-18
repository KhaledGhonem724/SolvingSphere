
<?php

use App\Http\Controllers\Containers\SheetController;

Route::middleware('auth')->group(function () {
    Route::get('/sheet', [SheetController::class, 'index'])->name('sheet.index');
    Route::get('/sheet/create', [SheetController::class, 'create'])->name('sheet.create');
    Route::post('/sheet', [SheetController::class, 'store'])->name('sheet.store');
    Route::get('/sheet/{id}', [SheetController::class, 'show'])->name('sheet.show');
    Route::post('/sheet/{sheet}/problems', [SheetController::class, 'add_problem'])->name('sheets.add_problem');
    Route::post('/sheet/{sheet}', [SheetController::class, 'post'])->name('sheets.post');
});

// Public shared sheets
Route::get('/sheet/shared/{token}', [SheetController::class, 'shared'])->name('sheet.shared');
