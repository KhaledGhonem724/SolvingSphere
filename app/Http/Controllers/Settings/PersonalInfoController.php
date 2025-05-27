<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\PersonalInfoUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PersonalInfoController extends Controller
{
    public function update(PersonalInfoUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Update user's name
            $user->update([
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
}
