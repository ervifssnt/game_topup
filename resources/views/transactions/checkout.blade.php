@extends('layout')

@section('title', 'Checkout')

@section('styles')
<style>
    .checkout-box {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .checkout-box h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
    }
    .transaction-details {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #e0e0e0;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .detail-label {
        font-weight: bold;
        color: #555;
    }
    .detail-value {
        color: #333;
    }
    .price-highlight {
        font-size: 20px;
        font-weight: bold;
        color: #00ff55;
    }
    .balance-info {
        background: #e8f5e9;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        text-align: center;
    }
    .insufficient-balance {
        background: #ffebee;
        color: #c62828;
    }
    .status-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: bold;
    }
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    .status-paid {
        background: #d4edda;
        color: #155724;
    }
    .btn-confirm {
        width: 100%;
        padding: 15px;
        background: #00ff55;
        color: black;
        font-weight: bold;
        border: none;
        border-radius: 5px;
        font-size: 18px;
        cursor: pointer;
        transition: background 0.3s;
    }
    .btn-confirm:hover {
        background: #00cc44;
        color: white;
    }
    .btn-back {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #666;
        text-decoration: none;
    }
</style>
@endsection

@section('content')
    <div class="checkout-box">
        <h2>Checkout</h2>

        <div class="transaction-details">
            <div class="detail-row">
                <span class="detail-label">Game:</span>
                <span class="detail-value">{{ $transaction->topupOption->game->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Account ID:</span>
                <span class="detail-value">{{ $transaction->account_id }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Package:</span>
                <span class="detail-value">
                    {{ $transaction->topupOption->amount ?? $transaction->coins . ' Coins' }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Price:</span>
                <span class="detail-value price-highlight">Rp {{ number_format($transaction->price, 0, ',', '.') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="status-badge status-{{ $transaction->status }}">
                    {{ ucfirst($transaction->status) }}
                </span>
            </div>
        </div>

        <div class="balance-info {{ $user->balance < $transaction->price ? 'insufficient-balance' : '' }}">
            <strong>Your Current Balance:</strong> Rp {{ number_format($user->balance, 0, ',', '.') }}
        </div>

        @if($transaction->status !== 'pending')
            <p style="text-align: center; color: #666;">
                This transaction has been {{ $transaction->status }}.
            </p>
            <a href="{{ route('home') }}" class="btn-back">← Back to Homepage</a>
        @elseif($user->balance < $transaction->price)
            <p style="color: red; text-align: center; font-weight: bold;">
                ⚠️ Not enough balance. Please top-up your wallet.
            </p>
            <a href="{{ route('home') }}" class="btn-back">← Back to Homepage</a>
        @else
            <form method="POST" action="{{ route('checkout.process') }}">
                @csrf
                <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                <button type="submit" class="btn-confirm">Confirm Checkout</button>
            </form>
            <a href="{{ route('home') }}" class="btn-back">← Cancel</a>
        @endif
    </div>
@endsection