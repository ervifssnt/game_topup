<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class PreventSessionHijacking
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Get stored fingerprint from session
            $storedIp = session('user_ip');
            $storedUserAgent = session('user_agent');
            
            // Current request data
            $currentIp = $request->ip();
            $currentUserAgent = $request->userAgent();
            
            // First time - store fingerprint
            if (!$storedIp) {
                session([
                    'user_ip' => $currentIp,
                    'user_agent' => $currentUserAgent,
                ]);
                
                AuditLog::log(
                    'session_fingerprint_created',
                    "Session fingerprint created for: {$user->username}",
                    'User',
                    $user->id
                );
                
                return $next($request);
            }
            
            // Check if IP changed (possible session hijacking)
// Find IP change detection (around line 45):
if ($storedIp !== $currentIp) {
    AuditLog::log(
        'suspicious_ip_change',
        "IP changed from {$storedIp} to {$currentIp} for user: {$user->username}",
        'User',
        $user->id
    );
    
    // ADD THIS - Send security alert email
    if ($user->email) {
        \Mail::to($user->email)->send(new \App\Mail\SecurityAlertMail(
            $user,
            'Suspicious Login - IP Address Changed',
            [
                'message' => 'Your session was terminated because your IP address changed. This could indicate unauthorized access.',
                'ip' => $currentIp,
                'location' => 'Unknown'
            ]
        ));
    }
    
    // Log out user for security
    Auth::logout();
    // ... rest of code
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')
                    ->with('error', 'Security alert: Your session was terminated due to suspicious activity (browser changed). Please login again.');
            }
        }
        
        return $next($request);
    }
}