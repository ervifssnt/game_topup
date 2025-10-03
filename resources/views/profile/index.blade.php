@extends('layouts.main')

@section('title', 'Dashboard - UP STORE')

@section('styles')
<style>
    .dashboard-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .page-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 30px;
        color: white;
    }
    
    /* Profile Info Card */
    .profile-card {
        background: #2a2a2a;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid #3a3a3a;
    }
    
    .profile-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF8C00 0%, #ff9d1f 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 700;
        color: white;
    }
    
    .profile-details h3 {
        font-size: 18px;
        margin-bottom: 4px;
        color: white;
    }
    
    .profile-details p {
        color: #999;
        font-size: 14px;
    }
    
    .balance-box {
        background: #1a1a1a;
        border: 2px solid #FF8C00;
        border-radius: 12px;
        padding: 20px 30px;
        text-align: right;
    }
    
    .balance-label {
        font-size: 14px;
        color: #999;
        margin-bottom: 8px;
    }
    
    .balance-amount {
        font-size: 24px;
        font-weight: 700;
        color: #FF8C00;
    }
    
    /* Transaction Overview */
    .overview-section {
        margin-bottom: 30px;
    }
    
    .section-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
        color: white;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    
    .stat-card {
        background: #2a2a2a;
        border: 1px solid #3a3a3a;
        border-radius: 12px;
        padding: 25px 20px;
        text-align: center;
        transition: all 0.3s;
    }
    
    .stat-card:hover {
        border-color: #FF8C00;
        transform: translateY(-4px);
    }
    
    .stat-number {
        font-size: 36px;
        font-weight: 700;
        color: white;
        margin-bottom: 8px;
    }
    
    .stat-label {
        font-size: 13px;
        color: #999;
        text-transform: capitalize;
    }
    
    /* Recent Transactions */
    .transactions-list {
        background: #2a2a2a;
        border: 1px solid #3a3a3a;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .transaction-item {
        padding: 20px 25px;
        border-bottom: 1px solid #3a3a3a;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.3s;
    }
    
    .transaction-item:last-child {
        border-bottom: none;
    }
    
    .transaction-item:hover {
        background: #333;
    }
    
    .transaction-info {
        display: flex;
        gap: 16px;
        align-items: center;
    }
    
    .transaction-icon {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        background: #3a3a3a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    
    .transaction-details h4 {
        font-size: 15px;
        margin-bottom: 4px;
        color: white;
    }
    
    .transaction-details p {
        font-size: 13px;
        color: #999;
    }
    
    .transaction-right {
        text-align: right;
    }
    
    .transaction-amount {
        font-size: 16px;
        font-weight: 600;
        color: white;
        margin-bottom: 4px;
    }
    
    .transaction-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
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
    
    .view-all-link {
        display: block;
        text-align: center;
        padding: 20px;
        color: #FF8C00;
        text-decoration: none;
        font-weight: 600;
        border-top: 1px solid #3a3a3a;
    }
    
    .view-all-link:hover {
        background: #333;
    }
    
    .no-transactions {
        padding: 60px 20px;
        text-align: center;
        color: #666;
    }
    
    @media (max-width: 768px) {
        .profile-card {
            flex-direction: column;
            gap: 20px;
        }
        
        .balance-box {
            width: 100%;
            text-align: center;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .transaction-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        
        .transaction-right {
            text-align: left;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <h1 class="page-title">Info Profile</h1>
    
    <!-- Profile Card -->
    <div class="profile-card">
        <div class="profile-info">
            <div class="profile-avatar">
                {{ strtoupper(substr($user->username, 0, 1)) }}
            </div>
            <div class="profile-details">
                <h3>Hello, {{ $user->username }}!</h3>
                <p>{{ $user->phone }}</p>
            </div>
        </div>
        
        <div class="balance-box">
            <div class="balance-label">Balance</div>
            <div class="balance-amount">Rp. {{ number_format($user->balance, 2, ',', '.') }}</div>
        </div>
    </div>
    
    <!-- Transaction Overview -->
    <div class="overview-section">
        <h2 class="section-title">Transaction Overview</h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $totalOrders }}</div>
                <div class="stat-label">Total Order</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ $pendingOrders }}</div>
                <div class="stat-label">Pending</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ $processingOrders }}</div>
                <div class="stat-label">Processing</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ $successOrders }}</div>
                <div class="stat-label">Success</div>
            </div>
        </div>
    </div>
    
    <!-- Recent Transactions -->
    <div class="overview-section">
        <h2 class="section-title">Recent Transactions</h2>
        
        <div class="transactions-list">
            @if($recentTransactions->count() > 0)
                @foreach($recentTransactions as $transaction)
                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-icon">🎮</div>
                            <div class="transaction-details">
                                <h4>{{ $transaction->topupOption->game->name }}</h4>
                                <p>{{ $transaction->topupOption->amount ?? $transaction->coins . ' Coins' }} • {{ $transaction->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="transaction-right">
                            <div class="transaction-amount">Rp {{ number_format($transaction->price, 0, ',', '.') }}</div>
                            <span class="transaction-status status-{{ $transaction->status }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
                
                <a href="{{ route('profile.history') }}" class="view-all-link">
                    View All Transactions →
                </a>
            @else
                <div class="no-transactions">
                    <p>No transactions yet. Start shopping!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection