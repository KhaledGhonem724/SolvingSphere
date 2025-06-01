<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class HackerEarthController extends Controller
{
    private const SCRAPER_BASE_URL = 'http://localhost:8000';

    /**
     * Connect user's HackerEarth account
     */
    public function connect(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        try {
            // Call the Python scraper service
            $response = Http::timeout(60)->post(self::SCRAPER_BASE_URL . '/api/hackerearth-connect', [
                'username' => $request->username,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $profileData = $response->json();
                
                // Save the profile data to the user's record
                /** @var User $user */
                $user = Auth::user();
                $user->update([
                    'hackerearth_username' => $request->username,
                    'hackerearth_points' => $profileData['points'] ?? 0,
                    'hackerearth_contest_rating' => $profileData['contest_rating'] ?? 0,
                    'hackerearth_problems_solved' => $profileData['problems_solved'] ?? 0,
                    'hackerearth_solutions_submitted' => $profileData['solutions_submitted'] ?? 0,
                    'hackerearth_connected_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'HackerEarth account connected successfully!',
                    'data' => $profileData
                ]);
            } else {
                $errorMessage = $response->json()['detail'] ?? 'Failed to connect to HackerEarth';
                
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('HackerEarth connection error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Service temporarily unavailable. Please try again later.'
            ], 500);
        }
    }

    /**
     * Refresh user's HackerEarth profile data
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user->hackerearth_username) {
            return response()->json([
                'success' => false,
                'message' => 'No HackerEarth account connected'
            ], 400);
        }

        $request->validate([
            'password' => 'required|string|max:255',
        ]);

        try {
            // Call the Python scraper service
            $response = Http::timeout(60)->post(self::SCRAPER_BASE_URL . '/api/hackerearth-connect', [
                'username' => $user->hackerearth_username,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $profileData = $response->json();
                
                // Update the profile data
                /** @var User $user */
                $user = Auth::user();
                $user->update([
                    'hackerearth_points' => $profileData['points'] ?? 0,
                    'hackerearth_contest_rating' => $profileData['contest_rating'] ?? 0,
                    'hackerearth_problems_solved' => $profileData['problems_solved'] ?? 0,
                    'hackerearth_solutions_submitted' => $profileData['solutions_submitted'] ?? 0,
                    'hackerearth_updated_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'HackerEarth profile updated successfully!',
                    'data' => $profileData
                ]);
            } else {
                $errorMessage = $response->json()['detail'] ?? 'Failed to refresh HackerEarth data';
                
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('HackerEarth refresh error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Service temporarily unavailable. Please try again later.'
            ], 500);
        }
    }

    /**
     * Disconnect user's HackerEarth account
     */
    public function disconnect(): JsonResponse
    {
        /** @var User $user */
                $user = Auth::user();
        $user->update([
            'hackerearth_username' => null,
            'hackerearth_points' => null,
            'hackerearth_contest_rating' => null,
            'hackerearth_problems_solved' => null,
            'hackerearth_solutions_submitted' => null,
            'hackerearth_connected_at' => null,
            'hackerearth_updated_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'HackerEarth account disconnected successfully!'
        ]);
    }
} 