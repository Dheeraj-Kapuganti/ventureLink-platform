<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all users with search and filter capabilities.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // 1. Keyword search (by name or email)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 2. Role filter (admin, founder, investor)
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->latest()->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Toggle the block/unblock status of a user.
     */
    public function toggleBlock($id)
    {
        $user = User::findOrFail($id);

        // Security safeguard: Admins cannot block themselves
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Security Alert: You cannot block your own administrative account.');
        }

        // Security safeguard: Do not allow blocking other admins easily from this panel
        if ($user->role === 'admin' && Auth::user()->role !== 'admin') {
            return back()->with('error', 'Access Denied: You do not have permissions to modify another admin.');
        }

        // Toggle status
        $isBlocked = ($user->status === 'blocked');
        $user->status = $isBlocked ? 'active' : 'blocked';
        $user->save();

        $action = $isBlocked ? 'unblocked' : 'blocked';

        return back()->with('success', "User '{$user->name}' has been successfully {$action}.");
    }

    /**
     * Securely delete a user account from the platform.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Security safeguard: Admins cannot delete themselves
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Security Alert: You cannot delete your own administrative account.');
        }

        // Clean up user startups if founder
        if ($user->role === 'founder') {
            foreach ($user->startups as $startup) {
                if ($startup->logo) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($startup->logo);
                }
                if ($startup->banner) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($startup->banner);
                }
                $startup->delete();
            }
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "User account '{$user->name}' has been deleted from the database.");
    }
}
