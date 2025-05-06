<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200">
    <div class="min-h-screen flex items-center justify-center">
        <div class="card w-96 bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title justify-center text-2xl font-bold mb-6">Login</h2>
                
                @if (session('message'))
                <div class="alert alert-info mb-4">
                    {{ session('message') }}
                </div>
                @endif

                @if (session('status'))
                <div class="alert alert-success mb-4">
                    {{ session('status') }}
                </div>
                @endif
                
                @if ($errors->any())
                <div class="alert alert-error mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Email</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" class="input input-bordered" required autofocus />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Password</span>
                        </label>
                        <input type="password" name="password" class="input input-bordered" required />
                        @if (Route::has('password.request'))
                        <label class="label">
                            <a href="{{ route('password.request') }}" class="label-text-alt link link-hover">
                                Forgot password?
                            </a>
                        </label>
                        @endif
                    </div>

                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <span class="label-text">Remember me</span>
                            <input type="checkbox" name="remember" class="checkbox checkbox-primary" />
                        </label>
                    </div>

                    <div class="form-control mt-6 w-full">
                        <button type="submit" class="btn btn-primary w-full">Login</button>
                    </div>

                    @if (Route::has('register'))
                    <div class="text-center mt-4">
                        <a href="{{ route('register') }}" class="link link-hover">
                            Don't have an account? Register
                        </a>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</body>
</html>
