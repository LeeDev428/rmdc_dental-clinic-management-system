<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    /**
     * Display the admin's profile form.
     */
    public function edit(Request $request): View
    {
        $admin = $request->user();
        
        return view('admin.profile.edit', [
            'admin' => $admin,
        ]);
    }

    /**
     * Update the admin's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $admin = $request->user();
        
        // Validate and update admin profile
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        
        // Update the admin's profile with the validated data
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->bio = $validated['bio'] ?? null;
        
        // Handle avatar upload if exists
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists and it's not the default
            if ($admin->avatar && $admin->avatar !== 'img/default-dp.jpg' && Storage::disk('public')->exists($admin->avatar)) {
                Storage::disk('public')->delete($admin->avatar);
            }
            
            // Store new avatar
            $admin->avatar = $request->file('avatar')->store('avatars', 'public');
        }
        
        $admin->save();
        
        return redirect()->route('admin.profile.edit')->with('status', 'Profile updated successfully!');
    }

    /**
     * Update the admin's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'Password updated successfully!');
    }
}
