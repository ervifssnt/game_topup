<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetRequest;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasswordResetController extends Controller
{
    // Show request form
    public function showRequestForm()
    {
        return view('auth.password-reset-request');
    }

    // Submit password reset request
    public function submitRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'reason' => 'required|string|max:500',
        ], [
            'email.exists' => 'No account found with this email address.',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        // Check if user already has pending request
        $existingRequest = PasswordResetRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'You already have a pending password reset request. Please wait for admin approval.');
        }

        PasswordResetRequest::create([
            'user_id' => $user->id,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        AuditLog::log(
            'password_reset_requested',
            "User requested password reset: {$user->username}",
            'PasswordResetRequest',
            null,
            null,
            ['user_id' => $user->id, 'reason' => $request->reason]
        );

        return redirect()->route('login')->with('success', 'Password reset request submitted! An admin will review it shortly.');
    }

    // View request status (for logged-in users)
    public function viewStatus()
    {
        $requests = PasswordResetRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('auth.password-reset-status', compact('requests'));
    }
}