@extends('layouts.main')

@section('title', 'Transaction Receipt')

@section('styles')
<style>
    .receipt-container {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .receipt-card {
        background: #2a2a2a;
        border: 1px solid #3a3a3a;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    
    .receipt-header {
        background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
        padding: 40px 30px;
        text-align: center;
        color: white;
    }
    
    .receipt-icon {
        font-size: 64px;
        margin-bottom: 16px;
    }
    
    .receipt-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .receipt-date {
        font-size: 14px;
        opacity: 0.9;
    }
    
    .receipt-body {
        padding: 30px;
    }
    
    .receipt-section {
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid #3a3a3a;
    }
    
    .receipt-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .section-title {
        font-size: 14px;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        font-weight: 600;
    }
    
    .game-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .game-logo {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        background: #3a3a3a;
    }
    
    .game-name {
        font-size: 18px;
        font-weight: 600;
        color: white;
        margin-bottom: 4px;
    }
    
    .game-package {
        font-size: 14px;
        color: #999;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
    }
    
    .info-label {
        font-size: 14px;
        color: #999;
    }
    
    .info-value {
        font-size: 14px;
        color: white;
        font-weight: 500;
        text-align: right;
    }
    
    .price-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
    }
    
    .price-row.total {
        border-top: 2px solid #3a3a3a;
        margin-top: 12px;
        padding-top: 16px;
    }
    
    .price-label {
        font-size: 14px;
        color: #999;
    }
    
    .price-row.total .price-label {
        font-size: 16px;
        font-weight: 600;
        color: white;
    }
    
    .price-value {
        font-size: 14px;
        color: white;
        font-weight: 500;
    }
    
    .price-row.total .price-value {
        font-size: 20px;
        font-weight: 700;
        color: #4caf50;
    }
    
    .transaction-id {
        text-align: center;
        padding: 20px;
        background: #1a1a1a;
        border-radius: 8px;
        margin-bottom: 24px;
    }
    
    .transaction-id-label {
        font-size: 12px;
        color: #999;
        margin-bottom: 8px;
    }
    
    .transaction-id-value {
        font-size: 20px;
        font-weight: 700;
        color: #FF8C00;
        letter-spacing: 1px;
    }
    
    .action-buttons {
        display: flex;
        gap: 12px;
    }
    
    .btn-primary {
        flex: 1;
        padding: 14px;
        background: #FF8C00;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        background: #ff9d1f;
        transform: translateY(-2px);
    }
    
    .btn-secondary {
        padding: 14px 24px;
        background: transparent;
        border: 1px solid #4a4a4a;
        border-radius: 8px;
        color: white;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }
    
    .btn-secondary:hover {
        background: #3a3a3a;
        border-color: #FF8C00;
    }
    
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="receipt-container">
    <div class="receipt-card">
        <div class="receipt-header">
            <div class="receipt-icon">✓</div>
            <h1 class="receipt-title">Payment Successful</h1>
            <p class="receipt-date">{{ $transaction->updated_at->format('d M Y, H:i') }}</p>
        </div>
        
        <div class="receipt-body">
            <!-- Transaction ID -->
            <div class="transaction-id">
                <div class="transaction-id-label">Transaction ID</div>
                <div class="transaction-id-value">#{{ str_pad($transaction->id, 8, '0', STR_PAD_LEFT) }}</div>
            </div>
            
            <!-- Game Information -->
            <div class="receipt-section">
                <div class="section-title">Game Details</div>
                <div class="game-info">
                    @if($transaction->topupOption->game->logo)
                        <img src="{{ asset($transaction->topupOption->game->logo) }}" 
                             alt="{{ $transaction->topupOption->game->name }}" 
                             class="game-logo">
                    @else
                        <div class="game-logo"></div>
                    @endif
                    
                    <div>
                        <div class="game-name">{{ $transaction->topupOption->game->name }}</div>
                        <div class="game-package">{{ $transaction->topupOption->amount ?? $transaction->coins . ' Coins' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Account Information -->
            <div class="receipt-section">
                <div class="section-title">Account Information</div>
                <div class="info-row">
                    <span class="info-label">Account ID</span>
                    <span class="info-value">{{ $transaction->account_id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Player</span>
                    <span class="info-value">{{ $user->username }}</span>
                </div>
            </div>
            
            <!-- Payment Details -->
            <div class="receipt-section">
                <div class="section-title">Payment Summary</div>
                <div class="price-row">
                    <span class="price-label">Item Price</span>
                    <span class="price-value">Rp {{ number_format($transaction->price, 0, ',', '.') }}</span>
                </div>
                <div class="price-row">
                    <span class="price-label">Admin Fee</span>
                    <span class="price-value">Rp 0</span>
                </div>
                <div class="price-row total">
                    <span class="price-label">Total Paid</span>
                    <span class="price-value">Rp {{ number_format($transaction->price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('profile.history') }}" class="btn-secondary">View History</a>
        <a href="{{ route('home') }}" class="btn-primary">Back to Home</a>
    </div>
</div>
@endsection