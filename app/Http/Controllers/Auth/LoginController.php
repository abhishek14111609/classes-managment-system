<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form. Redirect already-authenticated users to their dashboard.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            $route = auth()->user()->dashboardRoute();
            if ($route && $route !== 'login') {
                return redirect()->route($route);
            }
            // User is authenticated but has no valid role — log them out
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
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

        // Determine if the input is an email or a username
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
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'username' => 'Your account has been deactivated. Please contact your administrator.',
                ]);
            }

            // Regenerate the session to prevent session fixation
            $request->session()->regenerate();

            $route = $user->dashboardRoute();

            // If no valid role is assigned, log the user out and show an error
            if (!$route || $route === 'login') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'username' => 'Your account has no role assigned. Please contact your administrator.',
                ]);
            }

            return redirect()->route($route);
        }

        throw ValidationException::withMessages([
            'username' => 'The credentials you entered are incorrect.',
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

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
