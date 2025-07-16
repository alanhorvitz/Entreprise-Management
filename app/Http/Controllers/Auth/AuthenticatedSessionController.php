<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        // Custom role/permission logic
        if ($user->hasRole('super_admin')) {
            // If they are super_admin, give them director role (add, don't replace)
            $user->assignRole('director');
        } 
        else if ($user->hasRole('admin')) {
            // Check if admin has director or supervisor type in employee record
            if ($user->employee && $user->employee->types()
                ->where('type', 'director')
                ->wherePivot('out_date', null)
                ->exists()) {
                // If they have director type, give them director role
                $user->assignRole('director');
            }
            else if ($user->employee && $user->employee->types()
                ->where('type', 'supervisor')
                ->wherePivot('out_date', null)
                ->exists()) {
                // If they are currently a supervisor, give them supervisor role
                $user->assignRole('supervisor');
            } else {
                // If they are admin but not a director or supervisor, give them employee role
                $user->assignRole('employee');
            }
        }
        else {
            // Default to employee role (add, don't replace)
            $user->assignRole('employee');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
