@extends('layouts.auth')

@section('title', 'Forgot Password - UP STORE')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-lg">
        <!-- Logo -->
        <div class="text-center mb-12">
            <div class="flex items-center justify-center gap-2 mb-2">
                <span class="text-4xl font-black italic text-primary">UP</span>
                <span class="text-2xl font-bold tracking-wider">STORE</span>
            </div>
        </div>

        <!-- Form Card -->
        <x-card class="max-w-md mx-auto">
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold mb-2">Forgot Password</h1>
                <p class="text-sm text-text-secondary">Enter your email and we'll send you a reset link</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <x-alert type="success" class="mb-6">
                    {{ session('success') }}
                </x-alert>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
                <x-alert type="error" class="mb-6">
                    <ul class="space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <x-input
                    label="Email Address"
                    name="email"
                    type="email"
                    placeholder="Enter your email address"
                    :value="old('email')"
                    required
                    autocomplete="email"
                />

                <x-button type="submit" variant="primary" class="w-full">
                    Send Reset Link
                </x-button>
            </form>

            <!-- Back to Login Link -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm text-primary hover:text-primary-400 font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Login
                </a>
            </div>
        </x-card>
    </div>
</div>
@endsection
