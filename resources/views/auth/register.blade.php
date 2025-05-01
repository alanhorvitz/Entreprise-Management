<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="modern">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200">
    <div class="min-h-screen flex items-center justify-center">
        <div class="card w-96 bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title justify-center text-2xl font-bold mb-6 text-black">Register</h2>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- First Name -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-black">First Name</span>
                        </label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="input input-bordered text-black" required autofocus />
                        @error('first_name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-black">Last Name</span>
                        </label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="input input-bordered text-black" required />
                        @error('last_name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-black">Email</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" class="input input-bordered text-black" required autocomplete="email" />
                        @error('email')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-black">Role</span>
                        </label>
                        <select name="role" class="select select-bordered text-black" required>
                            <option value="" disabled selected>Select a role</option>
                            <option value="director" {{ old('role') == 'director' ? 'selected' : '' }}>Director</option>
                            <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                            <option value="team_leader" {{ old('role') == 'team_leader' ? 'selected' : '' }}>Project Manager</option>
                            <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                        </select>
                        @error('role')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-black">Password</span>
                        </label>
                        <input type="password" name="password" class="input input-bordered text-black" required autocomplete="new-password" />
                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-black">Confirm Password</span>
                        </label>
                        <input type="password" name="password_confirmation" class="input input-bordered text-black" required autocomplete="new-password" />
                    </div>

                    <div class="form-control mt-6">
                        <button type="submit" class="btn btn-primary text-white">Register</button>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="link link-hover text-black">
                            Already registered? Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
