<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function index()
    {
        // Redirect if already logged in
        if (Auth::check()) {
            return redirect()->route('dashboard')->with('info', 'You are already logged in!');
        }
        
        return view('login.login');
    }

    /**
     * Handle login request (accepts email or username/name)
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|min:6',
        ]);

        // Detect if input is email or name
        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $credentials = [
            $loginField => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard')
                ->with('success', 'Login berhasil! Selamat datang, ' . Auth::user()->name);
        }

        return back()->withErrors([
            'login' => 'Email/username atau password salah.',
        ])->withInput($request->only('login'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Anda belum login!');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
