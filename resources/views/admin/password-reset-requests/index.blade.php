@extends('admin.layout')

@section('title', 'Password Reset Requests')

@section('content')
<div class="page-header">
    <h1>Password Reset Requests</h1>
    <p>Approve or reject user password reset requests</p>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr>
                        <td>#{{ $req->id }}</td>
                        <td>
                            <strong>{{ $req->user->username }}</strong><br>
                            <small style="color: #999;">{{ $req->user->email }}</small>
                        </td>
                        <td>{{ $req->reason }}</td>
                        <td>
                            <span class="badge badge-{{ $req->status === 'approved' ? 'paid' : ($req->status === 'rejected' ? 'failed' : 'pending') }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                        <td>{{ $req->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            @if($req->status === 'pending')
                                <button onclick="showApproveModal({{ $req->id }}, '{{ $req->user->username }}')" class="btn btn-primary btn-sm">
                                    Approve
                                </button>
                                <button onclick="showRejectModal({{ $req->id }})" class="btn btn-danger btn-sm">
                                    Reject
                                </button>
                            @else
                                <small style="color: #999;">
                                    By: {{ $req->processedBy->username ?? 'System' }}<br>
                                    {{ $req->processed_at->format('d M Y') }}
                                </small>
                            @endif
                        </td>
                    </tr>
                    @if($req->admin_notes && $req->status !== 'pending')
                        <tr>
                            <td colspan="6" style="background: #f8f8f8; padding: 12px 20px; font-size: 13px;">
                                <strong>Admin Notes:</strong> {{ $req->admin_notes }}
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px;">
                            No password reset requests yet
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="padding: 20px;">
        {{ $requests->links() }}
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 20px; color: #333;">Set New Password</h3>
        <p style="color: #666; margin-bottom: 20px;">
            Creating new password for: <strong id="approveUsername"></strong>
        </p>
        <form id="approveForm" method="POST">
            @csrf
            <div class="form-group">
                <label>New Password *</label>
                <input type="password" name="new_password" class="form-control" required 
                       placeholder="Must contain uppercase, lowercase, number, special char">
            </div>
            <div class="form-group">
                <label>Confirm Password *</label>
                <input type="password" name="new_password_confirmation" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Admin Notes (Optional)</label>
                <textarea name="admin_notes" class="form-control" rows="2" placeholder="Internal notes about this reset..."></textarea>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Set Password & Approve</button>
                <button type="button" onclick="closeModal()" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 20px; color: #333;">Reject Password Reset</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="form-group">
                <label>Reason for Rejection *</label>
                <textarea name="admin_notes" class="form-control" rows="3" placeholder="Explain why this request is rejected..." required></textarea>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 20px;">
                <button type="submit" class="btn btn-danger">Reject Request</button>
                <button type="button" onclick="closeModal()" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function showApproveModal(id, username) {
    document.getElementById('approveForm').action = '/admin/password-reset-requests/' + id + '/approve';
    document.getElementById('approveUsername').textContent = username;
    document.getElementById('approveModal').style.display = 'flex';
}

function showRejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/password-reset-requests/' + id + '/reject';
    document.getElementById('rejectModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('approveModal').style.display = 'none';
    document.getElementById('rejectModal').style.display = 'none';
}
</script>
@endsection