@extends('layouts.main')

@section('title', 'Transaction History - UP STORE')

@section('styles')
<style>
    .history-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .page-header {
        margin-bottom: 30px;
    }
    
    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: white;
        margin-bottom: 8px;
    }
    
    .page-subtitle {
        color: #999;
        font-size: 15px;
    }
    
    /* Transactions Table */
    .transactions-table {
        background: #2a2a2a;
        border: 1px solid #3a3a3a;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .table-header {
        background: #1a1a1a;
        padding: 20px 25px;
        display: grid;
        grid-template-columns: 2fr 1.5fr 1fr 1fr 0.8fr;
        gap: 20px;
        font-size: 13px;
        font-weight: 600;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .transaction-row {
        padding: 20px 25px;
        border-bottom: 1px solid #3a3a3a;
        display: grid;
        grid-template-columns: 2fr 1.5fr 1fr 1fr 0.8fr;
        gap: 20px;
        align-items: center;
        transition: background 0.3s;
    }
    
    .transaction-row:last-child {
        border-bottom: none;
    }
    
    .transaction-row:hover {
        background: #333;
    }
    
    .game-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .game-icon {
        width: 45px;
        height: 45px;
        border-radius: 8px;
        background: linear-gradient(135deg, #3a3a3a 0%, #2a2a2a 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    
    .game-details h4 {
        font-size: 15px;
        color: white;
        margin-bottom: 4px;
    }
    
    .game-details p {
        font-size: 13px;
        color: #999;
    }
    
    .transaction-date {
        font-size: 14px;
        color: #ccc;
    }
    
    .transaction-amount {
        font-size: 15px;
        font-weight: 600;
        color: white;
    }
    
    .transaction-id {
        font-size: 13px;
        color: #999;
        font-family: monospace;
    }
    
    .transaction-status {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
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
    
    .status-cancelled {
        background: #f0f0f0;
        color: #666;
    }
    
    /* Pagination */
    .pagination-wrapper {
        padding: 25px;
        display: flex;
        justify-content: center;
        border-top: 1px solid #3a3a3a;
    }
    
    .pagination {
        display: flex;
        gap: 8px;
        list-style: none;
    }
    
    .pagination li a,
    .pagination li span {
        padding: 10px 16px;
        background: #3a3a3a;
        border-radius: 8px;
        color: white;
        text-decoration: none;
        transition: all 0.3s;
        font-size: 14px;
    }
    
    .pagination li a:hover {
        background: #FF8C00;
    }
    
    .pagination li.active span {
        background: #FF8C00;
    }
    
    .pagination li.disabled span {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    /* Empty State */
    .empty-state {
        padding: 80px 20px;
        text-align: center;
    }
    
    .empty-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.3;
    }
    
    .empty-title {
        font-size: 20px;
        font-weight: 600;
        color: white;
        margin-bottom: 8px;
    }
    
    .empty-text {
        color: #999;
        margin-bottom: 24px;
    }
    
    .btn-primary {
        display: inline-block;
        padding: 12px 28px;
        background: #FF8C00;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        background: #ff9d1f;
        transform: translateY(-2px);
    }
    
    @media (max-width: 968px) {
        .table-header {
            display: none;
        }
        
        .transaction-row {
            grid-template-columns: 1fr;
            gap: 12px;
            padding: 20px;
        }
        
        .game-info {
            grid-column: 1;
        }
        
        .transaction-date,
        .transaction-amount,
        .transaction-id,
        .transaction-status {
            justify-self: start;
        }
    }
</style>
@endsection

@section('content')
<div class="history-container">
    <div class="page-header">
        <h1 class="page-title">Riwayat Transaksi</h1>
        <p class="page-subtitle">View your purchase history and transaction details</p>
    </div>
    
    <div class="transactions-table">
        @if($transactions->count() > 0)
            <div class="table-header">
                <div>Game</div>
                <div>Date</div>
                <div>Amount</div>
                <div>Transaction ID</div>
                <div>Status</div>
            </div>
            
            @foreach($transactions as $transaction)
                <div class="transaction-row">
                    <div class="game-info">
                        <div class="game-icon">🎮</div>
                        <div class="game-details">
                            <h4>{{ $transaction->topupOption->game->name }}</h4>
                            <p>{{ $transaction->topupOption->amount ?? $transaction->coins . ' Coins' }}</p>
                        </div>
                    </div>
                    
                    <div class="transaction-date">
                        {{ $transaction->created_at->format('d M Y, H:i') }}
                    </div>
                    
                    <div class="transaction-amount">
                        Rp {{ number_format($transaction->price, 0, ',', '.') }}
                    </div>
                    
                    <div class="transaction-id">
                        #{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                    
                    <div>
                        <span class="transaction-status status-{{ $transaction->status }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                </div>
            @endforeach
            
            @if($transactions->hasPages())
                <div class="pagination-wrapper">
                    {{ $transactions->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <h3 class="empty-title">No Transactions Yet</h3>
                <p class="empty-text">You haven't made any purchases yet. Start shopping for your favorite games!</p>
                <a href="{{ route('home') }}" class="btn-primary">Browse Games</a>
            </div>
        @endif
    </div>
</div>
@endsection