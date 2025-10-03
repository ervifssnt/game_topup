<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

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
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      // at least one lowercase
                'regex:/[A-Z]/',      // at least one uppercase  
                'regex:/[0-9]/',      // at least one number
                'regex:/[@$!%*#?&]/', // at least one special character
            ],
        ], [
            'phone.unique' => 'Nomor telepon sudah terdaftar.',
            'phone.regex' => 'Nomor telepon harus terdiri dari 10–15 digit.',
            'password.confirmed' => 'Password tidak sama.',
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        $user = User::create([
            'username' => $request->username,
            'phone' => $request->phone,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'balance' => 0.00,
        ]);

        // Log registration
        AuditLog::log(
            'register',
            "New user registered: {$user->username}",
            'User',
            $user->id,
            null,
            ['username' => $user->username, 'phone' => $user->phone]
        );

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

    // Rate limiting key
    $key = 'login:' . $request->email . '|' . $request->ip();

    // Check rate limit
    if (RateLimiter::tooManyAttempts($key, 5)) {
    $seconds = RateLimiter::availableIn($key);
    $this->logLoginAttempt($request->email, false);
    
    // Still increment failed attempts even when rate limited
    $user = User::where('email', $request->email)->first();
    if ($user) {
        $user->incrementFailedAttempts();
    }
    
    throw ValidationException::withMessages([
        'email' => ["Too many login attempts. Please try again in {$seconds} seconds."],
    ]);
}

    $user = User::where('email', $request->email)->first();

    // Check if account is locked
    if ($user && $user->isLocked()) {
        $this->logLoginAttempt($request->email, false);
        
        $minutesLeft = ceil(30 - now()->diffInMinutes($user->locked_at));
        
        throw ValidationException::withMessages([
            'email' => ["Account is locked due to too many failed login attempts. Try again in {$minutesLeft} minutes or contact administrator."],
        ]);
    }

    // Check credentials
    if ($user && Hash::check($request->password, $user->password_hash)) {
        RateLimiter::clear($key);
        $this->logLoginAttempt($request->email, true);
        
        // Reset failed attempts on successful login
        $user->resetFailedAttempts();
        
        AuditLog::log(
            'login',
            "User logged in: {$user->username}",
            'User',
            $user->id
        );

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    // Failed login
    RateLimiter::hit($key, 60);
    $this->logLoginAttempt($request->email, false);
    
    // Increment failed attempts if user exists
    if ($user) {
        $user->incrementFailedAttempts();
    }

    return back()->withErrors([
        'email' => 'Invalid email or password.',
    ])->withInput($request->only('email'));
}
    // Handle logout
    public function logout(Request $request)
    {
        $username = Auth::user()->username;
        
        AuditLog::log(
            'logout',
            "User logged out: {$username}",
            'User',
            Auth::id()
        );
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // Helper: Log login attempts
    private function logLoginAttempt($email, $successful)
    {
        DB::table('login_attempts')->insert([
            'email' => $email,
            'ip_address' => request()->ip(),
            'successful' => $successful,
            'user_agent' => request()->userAgent(),
            'attempted_at' => now(),
        ]);
    }
}