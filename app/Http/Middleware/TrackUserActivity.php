<?php

namespace App\Http\Middleware;
use App\Http\Controllers\Profiles\PersonalInfoController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user();
            $now = now();

            // Only update activity if:
            // 1. It's the first request of the session OR
            // 2. Last update was more than 30 minutes ago OR
            // 3. It's a new day since last update
            $shouldUpdate = false;

            // Check if we've recorded the last update time in session
            $lastUpdate = Session::get('last_activity_update');

            if (!$lastUpdate) {
                $shouldUpdate = true;
            } else {
                // Convert to Carbon instance
                $lastUpdateTime = \Carbon\Carbon::parse($lastUpdate);

                // Update if it's been more than 30 minutes
                if ($now->diffInMinutes($lastUpdateTime) > 30) {
                    $shouldUpdate = true;
                }

                // Update if it's a new day
                if ($now->startOfDay()->gt($lastUpdateTime->startOfDay())) {
                    $shouldUpdate = true;
                }
            }

            if ($shouldUpdate) {
                // Update the last active timestamp and streak
                app(PersonalInfoController::class)->updateLastActive();

                // Record this update time in the session
                Session::put('last_activity_update', $now->toDateTimeString());
            }
        }

        return $response;
    }
}
