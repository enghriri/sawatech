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
    

    public function create(): View
    {
        return view('welcome');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
            
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
    
            // Redirect based on role
            if (Auth::user()->hasRole('admin')) {
               return redirect()->route('filament.admin.pages.dashboard');
            } elseif (Auth::user()->hasRole('clinic')) {
                return redirect()->route('filament.clinic.pages.dashboard');
            } elseif (Auth::user()->hasRole('doctor')) {
                return redirect()->route('filament.doctor.pages.dashboard');
            } 
    
            return redirect()->route('dashboard');
        }
    
        return back()->withErrors(['email' => 'Invalid credentials']);
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
