@extends('admin.layout')

@section('title', 'Edit User')

@section('content')
<div class="page-header">
    <h1>Edit User</h1>
    <p>Update user information</p>
</div>

<div class="content-card">
    <div class="card-header">
        <h2>User Details</h2>
    </div>
    
    <div style="padding: 24px;">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}">
            </div>
            
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" required>
            </div>
            
            <div class="form-group">
                <label>Balance</label>
                <input type="number" step="0.01" name="balance" class="form-control" value="{{ $user->balance }}" required>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_admin" value="1" {{ $user->is_admin ? 'checked' : '' }}>
                    Make this user an admin
                </label>
            </div>
            
            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection