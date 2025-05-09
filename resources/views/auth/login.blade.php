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
    <style>
        /* Light theme */
        :where(:root),:root:has(input.theme-controller[value=light]:checked),[data-theme=light] {
            color-scheme: light;
            --color-base-100: oklch(100% 0 0);
            --color-base-200: oklch(98% 0 0);
            --color-base-300: oklch(95% 0 0);
            --color-base-content: oklch(21% 0.006 285.885);
            --color-primary: #dab540 !important;
            --color-primary-content: oklch(98% 0.001 106.423);
            --color-secondary: oklch(25% 0.12 264.92);
            --color-secondary-content: oklch(94% 0.028 342.258);
            --color-accent: oklch(55% 0.18 302.57);
            --color-accent-content: oklch(98% 0.001 106.423);
            --color-neutral: #343131;
            --color-neutral-content: oklch(92% 0.004 286.32);
            --color-info: oklch(74% 0.16 232.661);
            --color-info-content: oklch(29% 0.066 243.157);
            --color-success: oklch(76% 0.177 163.223);
            --color-success-content: oklch(37% 0.077 168.94);
            --color-warning: oklch(82% 0.189 84.429);
            --color-warning-content: oklch(41% 0.112 45.904);
            --color-error: oklch(71% 0.194 13.428);
            --color-error-content: oklch(27% 0.105 12.094);
            --radius-selector: 0.5rem;
            --radius-field: 0.25rem;
            --radius-box: 0.5rem;
            --size-selector: 0.25rem;
            --size-field: 0.25rem;
            --border: 1px;
            --depth: 1;
            --noise: 0;
        }

        /* Dark theme */
        [data-theme=dark] {
            color-scheme: dark;
            --color-base-100: oklch(20% 0 0);
            --color-base-200: oklch(15% 0 0); 
            --color-base-300: oklch(10% 0 0);
            --color-base-content: oklch(98% 0.003 285.885);
            --color-primary: #dab540 !important;
            --color-primary-content: oklch(15% 0.001 106.423);
            --color-secondary: oklch(70% 0.12 264.92);
            --color-secondary-content: oklch(15% 0.028 342.258);
            --color-accent: oklch(70% 0.18 302.57);
            --color-accent-content: oklch(15% 0.001 106.423);
            --color-neutral: #CFCFCF;
            --color-neutral-content: oklch(15% 0.004 286.32);
            --color-info: oklch(55% 0.16 232.661);
            --color-info-content: oklch(85% 0.066 243.157);
            --color-success: oklch(55% 0.177 163.223);
            --color-success-content: oklch(85% 0.077 168.94);
            --color-warning: oklch(65% 0.189 84.429);
            --color-warning-content: oklch(85% 0.112 45.904);
            --color-error: oklch(55% 0.194 13.428);
            --color-error-content: oklch(85% 0.105 12.094);
            --radius-selector: 0.5rem;
            --radius-field: 0.25rem;
            --radius-box: 0.5rem;
            --size-selector: 0.25rem;
            --size-field: 0.25rem;
            --border: 1px;
            --depth: 1;
            --noise: 0;
        }

        /* Custom primary color control for both themes */
        .btn-primary, 
        .bg-primary,
        .text-primary {
            color-scheme: light !important;
        }

        .btn-primary {
            background-color: #dab540 !important;
            border-color: #dab540 !important;
        }

        .btn-primary:hover {
            background-color: #c2a030 !important;
            border-color: #c2a030 !important;
        }

        /* Ensure text is readable on primary color background in both themes */
        [data-theme=light] .btn-primary {
            color: #ffffff !important;
        }

        [data-theme=dark] .btn-primary {
            color: #000000 !important;
        }

        .text-primary {
            color: #dab540 !important;
        }

        .bg-primary {
            background-color: #dab540 !important;
        }
    </style>
</head>
<body class="min-h-screen bg-base-200">
    <div class="min-h-screen flex">
        <!-- Image Section -->
        <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-primary/10 to-primary/30 items-center justify-center p-8 relative" style="background-image: linear-gradient(to bottom right, rgba(218, 181, 64, 0.1), rgba(218, 181, 64, 0.3));">
            <div class="max-w-xl">
                <img src="{{ asset('images/tasks.png') }}" alt="Task Management" class="w-full h-auto rounded-lg drop-shadow-xl">
                <div class="absolute bottom-8 left-0 right-0 text-center">
                    <h2 class="text-2xl font-bold text-primary" style="color: #dab540 !important;">Manage Your Projects Efficiently</h2>
                    <p class="text-base-content/70 mt-2">Organize, collaborate, and track your tasks in one place</p>
                </div>
            </div>
        </div>
        
        <!-- Login Form Section -->
        <div class="w-full lg:w-1/2 flex items-center justify-center">
            <div class="card w-full max-w-md bg-base-100 shadow-xl">
                <div class="card-body p-8">
                <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="card-title text-2xl font-bold">Welcome Back</h2>
                            <p class="text-base-content/70">Sign in to continue to your workspace</p>
                        </div>
                    <label class="swap swap-rotate">
                            <input type="checkbox" class="theme-controller" value="dark" />
                        <!-- sun icon -->
                        <svg class="swap-on fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/></svg>
                        <!-- moon icon -->
                        <svg class="swap-off fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"/></svg>
                    </label>
                </div>
                    
                    <!-- Mobile only image -->
                    <div class="lg:hidden flex flex-col items-center mb-6">
                        <div class="bg-gradient-to-r from-primary/20 to-primary/30 p-4 rounded-xl mb-3" style="background-image: linear-gradient(to right, rgba(218, 181, 64, 0.2), rgba(218, 181, 64, 0.3));">
                            <img src="{{ asset('images/tasks.png') }}" alt="Task Management" class="w-48 h-auto">
                        </div>
                        <p class="text-sm text-primary font-medium" style="color: #dab540 !important;">Task Management System</p>
                    </div>
                
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
                        <div class="form-control flex flex-col gap-2">
                        <label class="label">
                            <span class="label-text">Email</span>
                        </label>
                            <input type="email" name="email" value="{{ old('email') }}" class="input input-bordered w-full" required autofocus />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Password</span>
                        </label>
                            <input type="password" name="password" class="input input-bordered w-full" required />
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
                            <button type="submit" class="btn btn-primary w-full" style="background-color: #dab540; border-color: #dab540;">
                                Sign In
                            </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Theme toggler
        document.addEventListener('DOMContentLoaded', function() {
            const themeController = document.querySelector('.theme-controller');
            
            // Force-apply primary color styles to ensure they persist across theme changes
            function applyPrimaryColorStyles() {
                const primaryElements = document.querySelectorAll('.btn-primary, .text-primary, .bg-primary');
                primaryElements.forEach(el => {
                    if(el.classList.contains('btn-primary')) {
                        el.style.backgroundColor = '#dab540';
                        el.style.borderColor = '#dab540';
                    }
                    if(el.classList.contains('text-primary')) {
                        el.style.color = '#dab540';
                    }
                    if(el.classList.contains('bg-primary')) {
                        el.style.backgroundColor = '#dab540';
                    }
                });
            }
            
            // Set initial state based on system preference or saved preference
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            themeController.checked = savedTheme === 'dark';
            
            // Apply primary color styles initially
            applyPrimaryColorStyles();
            
            // Theme toggle handler
            themeController.addEventListener('change', function() {
                const theme = this.checked ? 'dark' : 'light';
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                
                // Reapply primary color styles after theme change
                setTimeout(applyPrimaryColorStyles, 50);
            });
        });
    </script>
</body>
</html>
