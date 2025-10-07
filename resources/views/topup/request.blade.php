@extends('layouts.main')

@section('title', 'Top-Up Wallet')

@section('styles')
<style>
    .topup-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .balance-header {
        background: linear-gradient(135deg, #FF8C00 0%, #ff9d1f 100%);
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        text-align: center;
        color: white;
    }
    
    .current-balance {
        font-size: 16px;
        opacity: 0.9;
        margin-bottom: 8px;
    }
    
    .balance-amount {
        font-size: 36px;
        font-weight: 700;
    }
    
    .pending-requests {
        background: #2a2a2a;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        border: 1px solid #3a3a3a;
    }
    
    .pending-title {
        font-size: 18px;
        font-weight: 600;
        color: white;
        margin-bottom: 16px;
    }
    
    .pending-item {
        background: #fff3cd;
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .pending-item:last-child {
        margin-bottom: 0;
    }
    
    .pending-amount {
        font-size: 20px;
        font-weight: 700;
        color: #856404;
    }
    
    .pending-status {
        color: #856404;
        font-size: 14px;
    }
    
    .topup-form-card {
        background: #2a2a2a;
        border-radius: 12px;
        padding: 30px;
        border: 1px solid #3a3a3a;
    }
    
    .form-title {
        font-size: 22px;
        font-weight: 700;
        color: white;
        margin-bottom: 24px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #ccc;
        margin-bottom: 8px;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        background: #1a1a1a;
        border: 1px solid #3a3a3a;
        border-radius: 8px;
        color: white;
        font-size: 15px;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #FF8C00;
    }
    
    select.form-control {
        cursor: pointer;
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }
    
    .form-hint {
        font-size: 13px;
        color: #999;
        margin-top: 6px;
    }
    
    .payment-methods {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-top: 8px;
    }
    
    .payment-option {
        position: relative;
    }
    
    .payment-option input[type="radio"] {
        position: absolute;
        opacity: 0;
    }
    
    .payment-label {
        display: block;
        padding: 16px;
        background: #3a3a3a;
        border: 2px solid #3a3a3a;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        color: white;
        font-size: 14px;
        font-weight: 500;
    }
    
    .payment-option input[type="radio"]:checked + .payment-label {
        border-color: #FF8C00;
        background: #404040;
    }
    
    .payment-label:hover {
        background: #404040;
    }
    
    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }
    
    .btn-submit {
        flex: 1;
        padding: 14px;
        background: #FF8C00;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-submit:hover {
        background: #ff9d1f;
        transform: translateY(-2px);
    }
    
    .btn-history {
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
    
    .btn-history:hover {
        background: #3a3a3a;
        border-color: #FF8C00;
    }
    
    @media (max-width: 768px) {
        .payment-methods {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="topup-container">
    <!-- Balance Header -->
    <div class="balance-header">
        <div class="current-balance">Current Balance</div>
        <div class="balance-amount">Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Pending Requests -->
    @if($pendingRequests->count() > 0)
        <div class="pending-requests">
            <div class="pending-title">⏳ Pending Requests</div>
            @foreach($pendingRequests as $req)
                <div class="pending-item">
                    <div>
                        <div class="pending-amount">Rp {{ number_format($req->amount, 0, ',', '.') }}</div>
                        <div class="pending-status">Waiting for admin approval</div>
                    </div>
                    <div style="text-align: right; font-size: 13px; color: #856404;">
                        {{ $req->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            @endforeach>
        </div>
    @endif

    <!-- Top-Up Form -->
    <div class="topup-form-card">
        <h2 class="form-title">New Top-Up Request</h2>
        
        <form action="{{ route('topup.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label>Amount (Rp) *</label>
                <input type="number" name="amount" class="form-control" min="10000" step="1000" placeholder="Enter amount (min. Rp 10,000)" value="{{ old('amount') }}" required>
                <div class="form-hint">Minimum top-up amount is Rp 10,000</div>
                @error('amount')
                    <span style="color: #ef5350; font-size: 13px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Payment Method *</label>
                <div class="payment-methods">
                    <div class="payment-option">
                        <input type="radio" name="payment_method" value="BCA" id="bca" required>
                        <label for="bca" class="payment-label">BCA</label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" name="payment_method" value="Mandiri" id="mandiri">
                        <label for="mandiri" class="payment-label">Mandiri</label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" name="payment_method" value="BNI" id="bni">
                        <label for="bni" class="payment-label">BNI</label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" name="payment_method" value="GoPay" id="gopay">
                        <label for="gopay" class="payment-label">GoPay</label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" name="payment_method" value="OVO" id="ovo">
                        <label for="ovo" class="payment-label">OVO</label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" name="payment_method" value="DANA" id="dana">
                        <label for="dana" class="payment-label">DANA</label>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Payment Proof (Optional)</label>
                <input type="file" name="proof_image" class="form-control" accept="image/jpeg,image/png">
                <div class="form-hint">Upload payment proof (Max 2MB, JPG/PNG only)</div>
                @error('proof_image')
                    <span style="color: #ef5350; font-size: 13px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Notes (Optional)</label>
                <textarea name="notes" class="form-control" placeholder="Any additional information...">{{ old('notes') }}</textarea>
            </div>
            
            <div class="action-buttons">
                <button type="submit" class="btn-submit">Submit Request</button>
                <a href="{{ route('topup.history') }}" class="btn-history">View History</a>
            </div>
        </form>
    </div>
</div>
@endsection