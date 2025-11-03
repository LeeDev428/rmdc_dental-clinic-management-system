<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;


class GoogleController extends Controller
{
    public function redirectToGoogle() 
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Debug: Log what we get from Google
            Log::info('Google user data:', [
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'id' => $googleUser->getId()
            ]);
            
            // Find existing user or create new one
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                Log::info('Found existing user: ' . $user->email);
                
                // Update avatar if user doesn't have one
                if (!$user->avatar) {
                    $user->update([
                        'avatar' => 'img/default-dp.jpg',
                    ]);
                }
                
                Auth::login($user);
            } else {
                Log::info('Creating new user for: ' . $googleUser->getEmail());
                
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'usertype' => 'user', // Explicitly set usertype for middleware
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'bio' => null, // Set bio to blank
                    'avatar' => 'img/default-dp.jpg', // Use default profile picture
                    'auth_provider' => 'google',
                    'auth_provider_id' => $googleUser->getId(),
                ]);
                Log::info('Created user: ' . $user->id);
                Auth::login($user);
            }
            
            Log::info('User logged in, redirecting to dashboard');
            return redirect('/dashboard');
            
        } catch (\Exception $e) {
            Log::error('Google OAuth error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect('/login')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
}
