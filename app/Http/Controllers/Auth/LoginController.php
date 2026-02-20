<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            $route = auth()->user()->dashboardRoute();
            if ($route) {
                return redirect()->route($route);
            }
        }
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Check if the input is an email or a username
        $loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = auth()->user();

            // Check if user account is active
            if (!$user->is_active) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'username' => 'Your account has been deactivated.',
                ]);
            }

            $request->session()->regenerate();

            $route = $user->dashboardRoute();
            return $route ? redirect()->route($route) : redirect()->route('home');
        }

        throw ValidationException::withMessages([
            'username' => 'Invalid credentials.',
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
