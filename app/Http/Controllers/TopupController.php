<?php

namespace App\Http\Controllers;

use App\Models\TopupRequest;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopupController extends Controller
{
    // Show topup request form
    public function showForm()
    {
        $pendingRequests = TopupRequest::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('topup.request', compact('pendingRequests'));
    }

    // Submit topup request
    public function submitRequest(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'payment_method' => 'required|string',
            'proof_image' => 'nullable|string',
            'notes' => 'nullable|string|max:500',
        ], [
            'amount.min' => 'Minimum top-up amount is Rp 10,000',
        ]);

        $topupRequest = TopupRequest::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'proof_image' => $request->proof_image,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        AuditLog::log(
            'topup_request',
            "User requested balance top-up: Rp " . number_format($topupRequest->amount, 0, ',', '.'),
            'TopupRequest',
            $topupRequest->id
        );

        return redirect()->route('topup.form')->with('success', 'Top-up request submitted! Please wait for admin approval.');
    }

    // User's topup history
    public function history()
    {
        $requests = TopupRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('topup.history', compact('requests'));
    }
}