<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PasswordResetController extends Controller
{
    // Show forgot password form (standard Laravel flow)
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Send reset link (standard Laravel flow)
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Generic response to prevent user enumeration
        $genericMessage = 'If an account exists with this email, a password reset link will be sent.';

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Create password reset token
            $token = Str::random(60);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => now(),
                ]
            );

            // Log the action
            AuditLog::log(
                'password_reset_link_requested',
                "Password reset link requested for: {$user->username}",
                'User',
                $user->id,
                null, // oldValues
                null, // newValues
                $user->id // explicit user_id
            );

            // TODO: Send email with reset link
            // For now, just log the token (in production, this would be emailed)
            \Log::info("Password reset token for {$request->email}: " . $token);
        }

        return back()->with('success', $genericMessage);
    }

    // Show reset password form with token
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Process password reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/',
            ],
        ], [
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
        ]);

        // Verify token
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        // Check if token is valid (Laravel uses Hash::check)
        if (!Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        // Check if token is not expired (60 minutes)
        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Reset token has expired. Please request a new one.']);
        }

        // Update password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        $user->password_hash = Hash::make($request->password);
        $user->save();

        // Delete the token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Log the action
        AuditLog::log(
            'password_reset_completed',
            "Password reset completed for: {$user->username}",
            'User',
            $user->id,
            null, // oldValues
            null, // newValues
            $user->id // explicit user_id
        );

        return redirect()->route('login')->with('success', 'Password has been reset successfully! You can now login with your new password.');
    }

    // Simple password reset (verify identity with email + username + phone)
    public function simpleReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'username' => 'required|string',
            'phone' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/',
            ],
        ], [
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
        ]);

        // Find user by email, username, and phone
        $user = User::where('email', $request->email)
            ->where('username', $request->username)
            ->where('phone', $request->phone)
            ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'The information provided does not match our records.'])->withInput();
        }

        // Update password
        $user->password_hash = Hash::make($request->password);
        $user->save();

        // Log the action
        AuditLog::log(
            'password_reset_simple',
            "Password reset via identity verification: {$user->username}",
            'User',
            $user->id,
            null, // oldValues
            null, // newValues
            $user->id // explicit user_id
        );

        return redirect()->route('login')->with('success', 'Password has been reset successfully! You can now login with your new password.');
    }
}