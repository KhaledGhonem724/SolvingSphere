 
<?php


use App\Models\Sheet;
use App\Models\Topic;
use App\Models\Problem;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Containers\SheetController;
use App\Http\Controllers\Containers\TopicController;
use App\Http\Controllers\Containers\RoadmapController;


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
    Route::delete('/sheet/{sheet}', [SheetController::class, 'destroy'])
        ->middleware(['auth'])
        ->name('sheet.destroy');
});

// Public sheet viewer
Route::get('/sheet/shared/{token}', [SheetController::class, 'shared'])->name('sheet.shared');

Route::middleware('auth')->group(function () {
    // 🔹 Topics List
    Route::get('/topics', [TopicController::class, 'index'])->name('topics.index');

    // 🔹 Create Topic Form
    Route::get('/topics/create', [TopicController::class, 'create'])->name('topics.create');

    // 🔹 Store New Topic
    Route::post('/topics', [TopicController::class, 'store'])->name('topics.store');

    // 🔹 Show Topic by ID
    Route::get('/topics/{id}', [TopicController::class, 'show'])->name('topics.show');

    // 🔹 Add Problems View
    // Add Problems View
    Route::get('/topics/{topic}/add-problem', function (Topic $topic) {
        $problems = Problem::all();

        return Inertia::render('Containers/AddTopicProblem', [
            'topic' => $topic,
            'problems' => $problems,
        ]);
    })->name('topics.add_problem_view');

    // Submit Problem to Topic
    Route::post('/topics/add-problem', [TopicController::class, 'add_Problem'])->name('topics.add_problem');



    // 🔹 Submit Problem to Topic with Description
    Route::post('/topics/add-problem', [TopicController::class, 'add_Problem'])->name('topics.add_problem');

    // 🔹 Load full topic (if needed for post functionality)
    Route::post('/topics/{topic}', [TopicController::class, 'post'])->name('topics.post');

    // 🔹 Remove Problem
    Route::delete('/topics/{topic}/remove-problem/{problemHandle}', [TopicController::class, 'removeProblem'])->name('topics.remove_problem');

    // 🔹 Delete Topic
    Route::delete('/topics/{topic}', [TopicController::class, 'destroy'])->name('topics.destroy');
});

// 🔸 Public Viewer (optional)
Route::get('/topics/shared/{token}', [TopicController::class, 'shared'])->name('topics.shared');


// 🛣️ Roadmaps (Requires Auth)
// 🔹 Roadmaps Routes
Route::middleware('auth')->group(function () {
    Route::get('/roadmaps', [RoadmapController::class, 'index'])->name('roadmaps.index');
    Route::get('/roadmaps/create', [RoadmapController::class, 'create'])->name('roadmaps.create');
    Route::post('/roadmaps', [RoadmapController::class, 'store'])->name('roadmaps.store');

    Route::get('/roadmaps/{id}', [RoadmapController::class, 'show'])->name('roadmaps.show');

    // 🔹 Add Topics
    Route::get('/roadmaps/{roadmap}/add-topic', [RoadmapController::class, 'addTopicsView'])->name('roadmaps.add_topic_view');
    Route::post('/roadmaps/add-topic', [RoadmapController::class, 'addTopics'])->name('roadmaps.add_topic');
    Route::delete('/roadmaps/{roadmap}/remove-topic/{topicId}', [RoadmapController::class, 'removeTopic'])
        ->name('roadmaps.remove_topic');

    // 🔹 Remove Topic (optional)
    Route::delete('/roadmaps/{roadmap}/remove-topic/{topicId}', [RoadmapController::class, 'removeTopic'])->name('roadmaps.remove_topic');

    // 🔹 Delete Roadmap
    Route::delete('/roadmaps/{roadmap}', [RoadmapController::class, 'destroy'])->name('roadmaps.destroy');
});

// 🔹 Public Roadmap View
Route::get('/roadmaps/shared/{token}', [RoadmapController::class, 'shared'])->name('roadmaps.shared');
