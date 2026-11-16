<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials + ['is_active' => true], $request->boolean('remember'))) {
            $request->session()->regenerate();
            LogAktivitas::catat($request->user(), 'Masuk ke aplikasi.');

            // Reload the destination so the browser starts with the new session.
            return Inertia::location(redirect()->intended(route('dashboard')));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            LogAktivitas::catat($user, 'Keluar dari aplikasi.');
        }

        return Inertia::location(route('login'));
    }
}
