<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Get the authenticated user
        $user = $request->user();
    
        // Pass the user to the view as 'profile' (you can change the name to match your needs)
        return view('profile.edit', [
            'profile' => $user,  // Passing the user as 'profile' variable
        ]);
    }

    /**
     * Update the user's profile information.
     */
public function update(Request $request)
{
    $user = $request->user();
    
    // Validate user profile data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'bio' => 'nullable|string',
        'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);
    
    // Handle avatar upload if exists
    if ($request->hasFile('avatar')) {
        // Delete old avatar if it exists and is not the default
        if ($user->avatar && !str_starts_with($user->avatar, 'img/')) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        // Store new avatar
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $avatarPath;
    }
    
    // Update the user's profile (exclude avatar from validated data)
    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->bio = $validated['bio'] ?? null;
    $user->save();
    
    // Set flash message after update
    return redirect()->route('profile.edit')->with('status', 'Profile updated successfully!');
}
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Optionally, delete profile-related files like the avatar
        if ($user->profile && $user->profile->avatar) {
            Storage::delete('public/' . $user->profile->avatar);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('status', 'Your account has been deleted successfully.');
    }
}
