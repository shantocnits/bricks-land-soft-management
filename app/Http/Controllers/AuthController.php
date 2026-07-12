<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'ইউজারনেম আবশ্যক।',
            'password.required' => 'পাসওয়ার্ড আবশ্যক।',
        ]);

        // Support logging in via email or name
        $loginField = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        // Find the user to check if username exists
        $user = \App\Models\User::where($loginField, $credentials['username'])->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'ইউজারনেম সঠিক নয়।',
            ])->onlyInput('username');
        }

        // Attempt login with credentials
        if (!Auth::attempt([$loginField => $credentials['username'], 'password' => $credentials['password']])) {
            return back()->withErrors([
                'password' => 'পাসওয়ার্ড সঠিক নয়।',
            ])->onlyInput('username');
        }

        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
