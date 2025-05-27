<?php

namespace App\Http\Controllers\Profiles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\PersonalInfoUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class PersonalInfoController extends Controller
{
    public function edit(): Response
    {
        $user = Auth::user();

        return Inertia::render('settings/personal-info', [
            'socialLinks' => [
                'linkedin_url' => $user->linkedin_url,
                'github_url' => $user->github_url,
                'portfolio_url' => $user->portfolio_url,
            ],
        ]);
    }

    public function update(PersonalInfoUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            DB::table('users')
                ->where('user_handle', $user->user_handle)
                ->update([
                    'name' => $validated['name'],
                    'linkedin_url' => $validated['linkedin_url'] ?? null,
                    'github_url' => $validated['github_url'] ?? null,
                    'portfolio_url' => $validated['portfolio_url'] ?? null,
                ]);

            DB::commit();
            return back()->with('success', 'Profile information updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update profile information. Please try again.');
        }
    }

    public function updateLastActive(): void
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            if (!$user) {
                Log::warning('Attempted to update last active for non-authenticated user');
                return;
            }

            $now = now();
            $today = $now->copy()->startOfDay();
            $yesterday = $today->copy()->subDay();

            // Get the user's last active date (normalized to start of day)
            $lastActiveDay = $user->last_active_at ? $user->last_active_at->copy()->startOfDay() : null;

            $updates = [];

            // Store the current last_active_at as previous_active_at before updating
            if ($user->last_active_at) {
                $updates['previous_active_at'] = $user->last_active_at;
            }

            // Update the current last_active_at to now
            $updates['last_active_at'] = $now;

            // Calculate streak
            if (!$lastActiveDay) {
                // First time user is active, start streak at 1
                $updates['current_streak'] = 1;
                $updates['max_streak'] = 1;
            } else if ($lastActiveDay->equalTo($today)) {
                // Already active today, maintain current streak
                // No changes needed to streak count
            } else if ($lastActiveDay->equalTo($yesterday)) {
                // Was active yesterday, increment streak
                $newStreak = ($user->current_streak ?? 0) + 1;
                $updates['current_streak'] = $newStreak;

                // Update max streak if needed
                if ($newStreak > ($user->max_streak ?? 0)) {
                    $updates['max_streak'] = $newStreak;
                }
            } else {
                // Gap in activity (not active yesterday or today before now)
                // Reset streak to 1 (today is the first day of new streak)
                $updates['current_streak'] = 1;
            }

            // Update the user record using DB facade
            $updated = DB::table('users')
                ->where('user_handle', $user->user_handle)
                ->update($updates);

            if ($updated === 0) {
                Log::warning('Failed to update user activity: No rows affected', [
                    'user_handle' => $user->user_handle,
                    'updates' => $updates
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update user activity: ' . $e->getMessage(), [
                'user_handle' => $user->user_handle ?? 'unknown',
                'exception' => $e
            ]);
        }
    }
}
