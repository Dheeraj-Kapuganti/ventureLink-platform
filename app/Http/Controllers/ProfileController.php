<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Validate the basic incoming requests
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id), // Ignore MongoDB ID
            ],
            'bio' => 'nullable|string',
            'contact_info' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed', // Optional Password Update
        ]);

        // 2. Update basic fields natively
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->bio = $validated['bio'] ?? null;
        $user->contact_info = $validated['contact_info'] ?? null;

        // 3. Optional Password Handling
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        // 4. Advanced Image Handling (Profile Picture)
        if ($request->hasFile('profile_image')) {
            // Delete old profile picture off disk securely if one existed
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            // Generate and save new profile path
            $path = $request->file('profile_image')->store('users/profiles', 'public');
            $user->profile_image = $path;
        }

        // 5. Save the updated MongoDB document
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}
