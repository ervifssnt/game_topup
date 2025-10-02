@extends('layout')

@section('title', 'Top Up - ' . $game->name)

@section('styles')
<style>
    .topup-form {
        max-width: 500px;
        margin: 0 auto;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .topup-form h2 {
        text-align: center;
        margin-bottom: 30px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }
    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        box-sizing: border-box;
    }
    .form-group select {
        cursor: pointer;
    }
    .btn-submit {
        width: 100%;
        padding: 15px;
        background: #00ff55;
        color: black;
        font-weight: bold;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }
    .btn-submit:hover {
        background: #00cc44;
        color: white;
    }
    .error-message {
        color: red;
        font-size: 14px;
        margin-top: 5px;
    }
</style>
@endsection

@section('content')
    <div class="topup-form">
        <h2>Top Up {{ $game->name }}</h2>

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('topup.store') }}">
            @csrf

            <div class="form-group">
                <label for="account_id">Account ID / Username:</label>
                <input type="text" 
                       id="account_id" 
                       name="account_id" 
                       value="{{ old('account_id') }}"
                       placeholder="Enter your game account ID" 
                       required>
                @error('account_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="topup_option_id">Select Package:</label>
                <select id="topup_option_id" name="topup_option_id" required>
                    <option value="">-- Choose Package --</option>
                    @foreach($game->topupOptions as $option)
                        <option value="{{ $option->id }}" {{ old('topup_option_id') == $option->id ? 'selected' : '' }}>
                            {{ $option->amount ?? $option->coins . ' Coins' }} - Rp {{ number_format($option->price, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                @error('topup_option_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Proceed to Checkout</button>
        </form>
    </div>
@endsection