<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;

class LoginController extends Controller
{
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Get the user's role from their app
            if ($user->hasRole('super_admin')) {
                // If they are super_admin, give them director role
                $user->syncRoles(['director']);
            } 
            else if ($user->hasRole('admin')) {
                // Check if admin has director or supervisor type in employee record
                if ($user->employee && $user->employee->types()
                    ->where('type', 'director')
                    ->wherePivot('out_date', null)
                    ->exists()) {
                    // If they have director type, give them director role
                    $user->syncRoles(['director']);
                }
                else if ($user->employee && $user->employee->types()
                    ->where('type', 'supervisor')
                    ->wherePivot('out_date', null)
                    ->exists()) {
                    // If they are currently a supervisor, give them supervisor role
                    $user->syncRoles(['supervisor']);
                } else {
                    // If they are admin but not a director or supervisor, give them employee role
                    $user->syncRoles(['employee']);
                }
            }
            else {
                // Default to employee role
                $user->syncRoles(['employee']);
            }
            
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
} 