@extends('layouts.main')

@section('title', 'Top-Up History')

@section('styles')
<style>
    .history-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .page-header {
        margin-bottom: 30px;
    }
    
    .page-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: white;
        margin-bottom: 8px;
    }
    
    .page-header p {
        color: #999;
        font-size: 15px;
    }
    
    .history-card {
        background: #2a2a2a;
        border-radius: 12px;
        border: 1px solid #3a3a3a;
        overflow: hidden;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table thead th {
        background: #1a1a1a;
        padding: 16px 20px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #3a3a3a;
    }
    
    .data-table tbody td {
        padding: 20px;
        border-bottom: 1px solid #3a3a3a;
        font-size: 15px;
        color: white;
    }
    
    .data-table tbody tr:hover {
        background: #333;
    }
    
    .data-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-paid {
        background: #d4edda;
        color: #155724;
    }
    
    .badge-failed {
        background: #f8d7da;
        color: #721c24;
    }
    
    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }
    
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
    
    .pagination-wrapper {
        padding: 20px;
        display: flex;
        justify-content: center;
        border-top: 1px solid #3a3a3a;
    }
    
    .back-button {
        margin-top: 24px;
    }
    
    @media (max-width: 768px) {
        .data-table {
            font-size: 13px;
        }
        
        .data-table thead {
            display: none;
        }
        
        .data-table tbody tr {
            display: block;
            margin-bottom: 16px;
            background: #2a2a2a;
            border-radius: 8px;
            border: 1px solid #3a3a3a;
        }
        
        .data-table tbody td {
            display: flex;
            justify-content: space-between;
            padding: 12px 16px;
            border: none;
        }
        
        .data-table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #999;
        }
    }
</style>
@endsection

@section('content')
<div class="history-container">
    <div class="page-header">
        <h1>Top-Up History</h1>
        <p>View all your balance top-up requests and their status</p>
    </div>

    <div class="history-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Admin Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                        <tr>
                            <td data-label="Date">{{ $request->created_at->format('d M Y, H:i') }}</td>
                            <td data-label="Amount">
                                <strong>Rp {{ number_format($request->amount, 0, ',', '.') }}</strong>
                            </td>
                            <td data-label="Payment">{{ $request->payment_method }}</td>
                            <td data-label="Status">
                                <span class="badge badge-{{ $request->status === 'approved' ? 'paid' : ($request->status === 'rejected' ? 'failed' : 'pending') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td data-label="Notes">
                                @if($request->admin_notes)
                                    {{ $request->admin_notes }}
                                @else
                                    <span style="color: #666;">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="border: none;">
                                <div class="empty-state">
                                    <div class="empty-icon">📋</div>
                                    <h3 class="empty-title">No Top-Up History</h3>
                                    <p class="empty-text">You haven't made any top-up requests yet</p>
                                    <a href="{{ route('topup.form') }}" class="btn-primary">Make Your First Top-Up</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($requests->hasPages())
            <div class="pagination-wrapper">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
    
    <div class="back-button">
        <a href="{{ route('topup.form') }}" class="btn-primary">New Top-Up Request</a>
    </div>
</div>
@endsection