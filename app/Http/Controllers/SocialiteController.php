<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function authProviderRedirection($provider)  
    {
        return Socialite::driver($provider)->redirect();
    }

    public function socialAuthentication($provider) 
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Debug: Log what we get from social provider
            Log::info($provider . ' user data:', [
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'id' => $socialUser->getId()
            ]);
            
            // Check if email is provided
            $email = $socialUser->getEmail();
            
            if (!$email) {
                // If no email, create one using provider ID
                $email = $socialUser->getId() . '@' . $provider . '.local';
                Log::warning($provider . ' did not provide email. Using fallback: ' . $email);
            }
            
            // Find existing user or create new one
            $user = User::where('email', $email)
                       ->orWhere('auth_provider_id', $socialUser->getId())
                       ->first();
            
            if ($user) {
                Log::info('Found existing user: ' . $user->email);
                
                // Update provider info if not set
                if (!$user->auth_provider) {
                    $user->update([
                        'auth_provider' => $provider,
                        'auth_provider_id' => $socialUser->getId(),
                        'email_verified_at' => now(),
                    ]);
                }
                
                // Update avatar if user doesn't have one
                if (!$user->avatar) {
                    $user->update([
                        'avatar' => 'img/default-dp.jpg',
                    ]);
                }
                
                Auth::login($user);
            } else {
                Log::info('Creating new user for: ' . $email);
                
                $user = User::create([
                    'name' => $socialUser->getName() ?? $provider . ' User',
                    'email' => $email,
                    'usertype' => 'user', // Explicitly set usertype for middleware
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => now(),
                    'bio' => null, // Set bio to blank
                    'avatar' => 'img/default-dp.jpg', // Use default profile picture
                    'auth_provider' => $provider,
                    'auth_provider_id' => $socialUser->getId(),
                ]);
                Log::info('Created user: ' . $user->id);
                Auth::login($user);
            }
            
            Log::info('User logged in, redirecting to dashboard');
            return redirect('/dashboard');
            
        } catch (\Exception $e) {
            Log::error($provider . ' OAuth error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect('/login')->with('error', $provider . ' login failed: ' . $e->getMessage());
        }
    }
}
