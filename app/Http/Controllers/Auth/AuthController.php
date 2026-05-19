<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // ====== REGISTER ======
    
    // Show registration form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Process registration request
    public function register(Request $request)
    {
        // 1. Validate the user data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // expects password_confirmation field
            'role' => 'required|in:founder,investor', // Allow either founder or investor
        ]);

        // 2. Hash the password manually if not using automatic casts, 
        // though Laravel casts can handle it, it's safer to hash here explicitly for beginners
        $validatedData['password'] = Hash::make($validatedData['password']);

        // 3. Create the user in MongoDB
        $user = User::create($validatedData);

        // 4. Log the user in automatically
        Auth::login($user);

        // 5. Redirect to the dashboard
        return redirect()->route('dashboard');
    }


    // ====== LOGIN ======

    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Process login request
    public function login(Request $request)
    {
        // 1. Validate credentials
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // 2. Attempt login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        // 3. Fallback on failure
        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // ====== ADMIN LOGIN ======

    // Show Admin login form
    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    // Process Admin login request
    public function adminLogin(Request $request)
    {
        // 1. Validate credentials
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // 2. Attempt login
        if (Auth::attempt($credentials)) {
            // Verify if the user that just logged in is actually an admin
            if (Auth::user()->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->route('admin.panel');
            } else {
                // If they logged in but aren't an admin, kick them out
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Access Denied: You do not have admin privileges.',
                ]);
            }
        }

        // 3. Fallback on failure
        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our system records.',
        ]);
    }


    // ====== LOGOUT ======
    
    // Process logout request
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
