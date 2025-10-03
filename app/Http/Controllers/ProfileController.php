<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Show user dashboard/profile
    public function index()
    {
        $user = Auth::user();
        
        // Get transaction counts by status
        $totalOrders = $user->transactions()->count();
        $pendingOrders = $user->transactions()->where('status', 'pending')->count();
        $processingOrders = $user->transactions()->where('status', 'paid')->count();
        $successOrders = $user->transactions()->where('status', 'paid')->count();
        
        // Get recent transactions
        $recentTransactions = $user->transactions()
            ->with('topupOption.game')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('profile.index', compact(
            'user',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'successOrders',
            'recentTransactions'
        ));
    }
    
    // Show transaction history (Riwayat)
    public function history()
    {
        $user = Auth::user();
        
        // Get all transactions with pagination
        $transactions = $user->transactions()
            ->with('topupOption.game')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('profile.history', compact('transactions'));
    }
}