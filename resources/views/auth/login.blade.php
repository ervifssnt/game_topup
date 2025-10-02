<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            background-color: #1a1a1a;
            font-family: Arial, sans-serif;
            color: white;
        }
        .topbar {
            background: #2b2b2b;
            height: 100px;
            display: flex;
            align-items: center;
            padding: 0 40px;
        }
        .topbar img {
            height: 60px;
            margin-right: 12px;
        }
        .topbar span {
            font-weight: bold;
            font-size: 28px;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 80px;
        }
        h2 {
            font-size: 36px;
            margin-bottom: 40px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 350px;
        }
        input {
            width: 100%;
            padding: 14px;
            margin: 12px 0;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            text-align: center;
            background-color: #d9d9d9;
            color: #000;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 14px;
            background-color: #444;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            margin-top: 15px;
            cursor: pointer;
        }
        button:hover {
            background-color: #666;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            width: 350px;
        }
        .register-link {
            margin-top: 20px;
            color: #ffcc00;
        }
        .register-link a {
            color: #ffcc00;
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="topbar">
        <img src="{{ asset('images/logo.png') }}" alt="UP Store Logo">
        <span>Game Topup</span>
    </div>

    <!-- Login Form -->
    <div class="container">
        <h2>Login</h2>

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="email" 
                   name="email" 
                   placeholder="Email" 
                   value="{{ old('email') }}"
                   required>
            
            <input type="password" 
                   name="password" 
                   placeholder="Password" 
                   required>
            
            <button type="submit">Login</button>
        </form>

        <div class="register-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
        </div>
    </div>
</body>
</html>