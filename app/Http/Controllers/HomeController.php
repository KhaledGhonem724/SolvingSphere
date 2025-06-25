<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\Sheet;
use App\Models\Topic;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProblem = Problem::inRandomOrder()->first();
        $recentSheets = Sheet::latest()->take(4)->get();
        $recentTopics = Topic::latest()->take(4)->get();

        return Inertia::render('Home', [
            'featuredProblem' => $featuredProblem,
            'recentSheets' => $recentSheets,
            'recentTopics' => $recentTopics,
        ]);
    }
}
