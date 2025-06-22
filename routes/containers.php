 
<?php


use App\Models\Sheet;
use App\Models\Problem;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Containers\SheetController;

Route::middleware('auth')->group(function () {
    Route::get('/sheet', [SheetController::class, 'index'])->name('sheet.index');
    Route::get('/sheet/create', [SheetController::class, 'create'])->name('sheet.create');
    Route::post('/sheet', [SheetController::class, 'store'])->name('sheet.store');
    Route::get('/sheet/{id}', [SheetController::class, 'show'])->name('sheet.show');

    // ✅ Add Problems to Sheet View
    Route::get('/sheet/{sheet}/add-problem', function (Sheet $sheet) {
        $problems = Problem::all(); // ممكن تضيف فلترة أو paginate حسب الحاجة

        return Inertia::render('Containers/AddProblem', [
            'sheet' => $sheet,
            'problems' => $problems,
        ]);
    })->name('sheet.add_problem_view');

    // ✅ Submit Selected Problems
    Route::post('/sheet/add-problem', [SheetController::class, 'add_problem'])->name('sheet.add_problem');

    Route::post('/sheet/{sheet}', [SheetController::class, 'post'])->name('sheet.post');
    Route::delete('/sheet/{sheet}/remove-problem/{problem}', [SheetController::class, 'removeProblem'])->name('sheet.remove_problem');
});

// Public sheet viewer
Route::get('/sheet/shared/{token}', [SheetController::class, 'shared'])->name('sheet.shared');
