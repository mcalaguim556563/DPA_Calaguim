<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — DPA Calaguim</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
</head>
<body>
<div class="auth-screen">
    <div class="auth-card">
        <div class="auth-brand">
            <div class="logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </div>
            <h1>Rice POS System</h1>
            <p>DPA Calaguim — Sign in to continue</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success" style="margin-bottom:16px">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" style="margin-bottom:16px">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email address</label>
                <input id="email" type="email" name="email" class="form-control"
                       value="{{ old('email') }}" required autofocus autocomplete="username"
                       placeholder="admin@example.com">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control"
                       required autocomplete="current-password" placeholder="••••••••">
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--text-muted);cursor:pointer">
                    <input type="checkbox" name="remember" id="remember_me" style="accent-color:var(--primary)">
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size:13px;color:var(--primary);text-decoration:none">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:10px">
                Sign In
            </button>
        </form>
    </div>
</div>
</body>
</html>
