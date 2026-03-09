<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

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
            return redirect()->route('home');
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
            $user->loadMissing(['roles', 'teacher', 'student']);

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

            if ($user->roles->isEmpty()) {
                $roleName = null;

                if ($user->student) {
                    $roleName = 'student';
                } elseif ($user->teacher) {
                    $roleName = 'teacher';
                } elseif ($user->school_id) {
                    $roleName = 'school_admin';
                } else {
                    $roleName = 'super_admin';
                }

                if ($roleName) {
                    Role::firstOrCreate([
                        'name' => $roleName,
                        'guard_name' => 'web',
                    ]);

                    $user->assignRole($roleName);
                }
            }

            $route = $user->dashboardRoute();

            if ($route && $route !== 'login') {
                return redirect()->intended(route($route));
            }

            return redirect()->intended(route('home'))
                ->with('success', 'Logged in successfully.');
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
