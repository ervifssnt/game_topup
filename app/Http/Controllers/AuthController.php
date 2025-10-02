<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show register form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone|regex:/^[0-9]{10,15}$/',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'phone.unique' => 'Nomor telepon sudah terdaftar.',
            'phone.regex' => 'Nomor telepon harus terdiri dari 10–15 digit.',
            'password.confirmed' => 'Password tidak sama.',
        ]);

        $user = User::create([
            'username' => $request->username,
            'phone' => $request->phone,
            'password_hash' => Hash::make($request->password),
            'balance' => 0.00,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registrasi berhasil!');
    }

    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password_hash)) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput();
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}