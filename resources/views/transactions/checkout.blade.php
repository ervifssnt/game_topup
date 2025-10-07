@extends('layouts.main')

@section('title', 'Checkout - UP STORE')

@section('styles')
<style>
    .checkout-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .page-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 30px;
        color: white;
    }
    
    /* Order Summary Card */
    .order-card {
        background: #2a2a2a;
        border: 1px solid #3a3a3a;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    
    .order-header {
        background: #1a1a1a;
        padding: 20px 30px;
        border-bottom: 1px solid #3a3a3a;
    }
    
    .order-header h2 {
        font-size: 18px;
        font-weight: 600;
        color: white;
    }
    
    .order-body {
        padding: 30px;
    }
    
    .game-summary {
        display: flex;
        align-items: center;
        gap: 20px;
        padding-bottom: 24px;
        border-bottom: 1px solid #3a3a3a;
        margin-bottom: 24px;
    }
    
    .game-image {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        background: linear-gradient(135deg, #3a3a3a 0%, #2a2a2a 100%);
    }
    
    .game-details h3 {
        font-size: 18px;
        font-weight: 600;
        color: white;
        margin-bottom: 4px;
    }
    
    .game-details p {
        font-size: 14px;
        color: #999;
    }
    
    .order-details {
        margin-bottom: 24px;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
    }
    
    .detail-label {
        font-size: 14px;
        color: #999;
    }
    
    .detail-value {
        font-size: 15px;
        color: white;
        font-weight: 500;
    }
    
    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
    }
    
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-paid {
        background: #d4edda;
        color: #155724;
    }
    
    .status-failed {
        background: #f8d7da;
        color: #721c24;
    }
    
    .price-summary {
        background: #1a1a1a;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 24px;
    }
    
    .price-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
    }
    
    .price-row.total {
        border-top: 2px solid #3a3a3a;
        margin-top: 12px;
        padding-top: 16px;
    }
    
    .price-row.total .price-label {
        font-size: 16px;
        font-weight: 600;
        color: white;
    }
    
    .price-row.total .price-value {
        font-size: 20px;
        font-weight: 700;
        color: #FF8C00;
    }
    
    .price-label {
        font-size: 14px;
        color: #999;
    }
    
    .price-value {
        font-size: 15px;
        color: white;
        font-weight: 500;
    }
    
    /* Promo Code */
    .promo-section {
        margin-bottom: 24px;
    }
    
    .promo-input-group {
        display: flex;
        gap: 12px;
    }
    
    .promo-input {
        flex: 1;
        padding: 12px 16px;
        background: #1a1a1a;
        border: 1px solid #3a3a3a;
        border-radius: 8px;
        color: white;
        font-size: 15px;
    }
    
    .promo-input:focus {
        outline: none;
        border-color: #FF8C00;
    }
    
    .promo-label {
        font-size: 14px;
        color: #ccc;
        margin-bottom: 8px;
        display: block;
    }
    
    /* Balance Card */
    .balance-card {
        background: #2a2a2a;
        border: 1px solid #3a3a3a;
        border-radius: 12px;
        padding: 24px 30px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .balance-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .balance-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #FF8C00 0%, #ff9d1f 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .balance-text h3 {
        font-size: 14px;
        color: #999;
        margin-bottom: 4px;
        font-weight: 500;
    }
    
    .balance-amount {
        font-size: 20px;
        font-weight: 700;
        color: white;
    }
    
    .balance-sufficient {
        color: #4caf50;
        font-size: 14px;
        font-weight: 600;
    }
    
    .balance-insufficient {
        color: #f44336;
        font-size: 14px;
        font-weight: 600;
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 16px;
    }
    
    .btn-confirm {
        flex: 1;
        padding: 16px;
        background: #FF8C00;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-confirm:hover {
        background: #ff9d1f;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 140, 0, 0.4);
    }
    
    .btn-confirm:disabled {
        background: #3a3a3a;
        color: #666;
        cursor: not-allowed;
        transform: none;
    }
    
    .btn-cancel {
        padding: 16px 32px;
        background: transparent;
        border: 1px solid #4a4a4a;
        border-radius: 8px;
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }
    
    .btn-cancel:hover {
        background: #3a3a3a;
        border-color: #FF8C00;
    }
    
    /* Alert Messages */
    .alert {
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .alert-success {
        background: #1e4620;
        border: 1px solid #2e7d32;
        color: #66bb6a;
    }
    
    .alert-error {
        background: #4a1a1a;
        border: 1px solid #d32f2f;
        color: #ef5350;
    }
    
    .alert-icon {
        font-size: 20px;
    }
    
    /* Transaction Complete */
    .transaction-complete {
        text-align: center;
        padding: 40px 20px;
    }
    
    .complete-icon {
        font-size: 80px;
        margin-bottom: 24px;
    }
    
    .complete-title {
        font-size: 24px;
        font-weight: 700;
        color: white;
        margin-bottom: 12px;
    }
    
    .complete-text {
        color: #999;
        margin-bottom: 32px;
        font-size: 15px;
    }
    
    @media (max-width: 768px) {
        .game-summary {
            flex-direction: column;
            text-align: center;
        }
        
        .balance-card {
            flex-direction: column;
            gap: 20px;
            text-align: center;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn-cancel {
            order: 2;
        }
        
        .promo-input-group {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="checkout-container">
    <h1 class="page-title">Checkout</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            <span class="alert-icon">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error">
            <span class="alert-icon">⚠</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    
    @if($transaction->status === 'paid')
        <!-- Transaction Complete State -->
        <div class="order-card">
            <div class="order-body">
                <div class="transaction-complete">
                    <div class="complete-icon">✓</div>
                    <h2 class="complete-title">Payment Successful!</h2>
                    <p class="complete-text">Your top-up has been processed successfully. The items will be added to your game account shortly.</p>
                    <a href="{{ route('home') }}" class="btn-confirm" style="max-width: 300px; margin: 0 auto; display: block;">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Order Summary -->
        <div class="order-card">
            <div class="order-header">
                <h2>Order Summary</h2>
            </div>
            
            <div class="order-body">
                <!-- Game Info -->
                <div class="game-summary">
                    @if($transaction->topupOption->game->logo)
                        <img src="{{ asset($transaction->topupOption->game->logo) }}" 
                             alt="{{ $transaction->topupOption->game->name }}" 
                             class="game-image">
                    @else
                        <div class="game-image"></div>
                    @endif
                    
                    <div class="game-details">
                        <h3>{{ $transaction->topupOption->game->name }}</h3>
                        <p>{{ $transaction->topupOption->amount ?? $transaction->coins . ' Coins' }}</p>
                    </div>
                </div>
                
                <!-- Order Details -->
                <div class="order-details">
                    <div class="detail-row">
                        <span class="detail-label">Account ID</span>
                        <span class="detail-value">{{ $transaction->account_id }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Package</span>
                        <span class="detail-value">{{ $transaction->topupOption->amount ?? $transaction->coins . ' Coins' }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Transaction ID</span>
                        <span class="detail-value">#{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Status</span>
                        <span class="status-badge status-{{ $transaction->status }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                </div>
                
                <!-- Price Summary -->
                <div class="price-summary">
                    <div class="price-row">
                        <span class="price-label">Item Price</span>
                        <span class="price-value">Rp {{ number_format($transaction->price, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="price-row">
                        <span class="price-label">Admin Fee</span>
                        <span class="price-value">Rp 0</span>
                    </div>
                    
                    <div class="price-row total">
                        <span class="price-label">Total Payment</span>
                        <span class="price-value">Rp {{ number_format($transaction->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Balance Card -->
        <div class="balance-card">
            <div class="balance-info">
                <div class="balance-icon">💰</div>
                <div class="balance-text">
                    <h3>Your Balance</h3>
                    <div class="balance-amount">Rp {{ number_format($user->balance, 0, ',', '.') }}</div>
                </div>
            </div>
            
            @if($user->balance >= $transaction->price)
                <span class="balance-sufficient">✓ Sufficient Balance</span>
            @else
                <span class="balance-insufficient">⚠ Insufficient Balance</span>
            @endif
        </div>
        
        <!-- Promo Code & Payment Form (always show) -->
        <form method="POST" action="{{ route('checkout.process') }}">
            @csrf
            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
            
            <!-- Promo Code Section -->
            <div class="promo-section">
                <label class="promo-label">Have a Promo Code?</label>
                <div class="promo-input-group">
                    <input type="text" 
                        name="promo_code" 
                        class="promo-input" 
                        placeholder="Enter promo code"
                        value="{{ old('promo_code') }}">
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('home') }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-confirm">Confirm Payment</button>
            </div>
        </form>
    @endif
</div>
@endsection