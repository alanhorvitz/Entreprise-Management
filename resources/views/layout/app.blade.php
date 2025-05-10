<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    {{-- Load assets using Laravel Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <style>
        :where(:root),:root:has(input.theme-controller[value=light]:checked),[data-theme=light] {
            color-scheme: light;
            --color-base-100: oklch(100% 0 0);
            --color-base-200: oklch(98% 0 0);
            --color-base-300: oklch(95% 0 0);
            --color-base-content: oklch(21% 0.006 285.885);
            --color-primary: #dab540;
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

        [data-theme=dark] {
            color-scheme: dark;
            --color-base-100: oklch(20% 0 0);
            --color-base-200: oklch(15% 0 0); 
            --color-base-300: oklch(10% 0 0);
            --color-base-content: oklch(98% 0.003 285.885);
            --color-primary: #dab540;
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

        /* Loader Styles */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--color-base-100);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out;
        }

        .loader-content {
            text-align: center;
        }

        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid var(--color-base-200);
            border-radius: 50%;
            border-top-color: var(--color-primary);
            animation: spin 1s ease-in-out infinite;
            margin: 0 auto 1rem;
        }

        .loader-text {
            color: var(--color-base-content);
            font-size: 1.1rem;
            font-weight: 500;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loader-hidden {
            opacity: 0;
            pointer-events: none;
        }
    </style>
</head>

<body class="font-sans antialiased bg-base-100/50">
    <!-- Page Loader -->
    <div id="page-loader" class="page-loader">
        <div class="loader-content">
            <div class="loader-spinner"></div>
            <div class="loader-text">Loading...</div>
        </div>
    </div>

    <div class="min-h-screen">
        @include('layout.sidebar')
        @include('layout.navbar')

        <!-- Flash Messages -->
        @if (session()->has('success') || session()->has('error'))
            <div class="fixed top-4 right-4 z-50 animate-fade-in-down">
                <div class="rounded-md p-4 {{ session()->has('success') ? 'bg-green-50' : 'bg-red-50' }} shadow-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            @if(session()->has('success'))
                                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @else
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="{{ session()->has('success') ? 'text-green-800' : 'text-red-800' }}">
                                {{ session()->get('success') ?? session()->get('error') }}
                            </p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button onclick="this.parentElement.parentElement.parentElement.parentElement.remove()" 
                                    class="{{ session()->has('success') ? 'text-green-500 hover:text-green-600' : 'text-red-500 hover:text-red-600' }} rounded-md p-1.5">
                                    <span class="sr-only">Dismiss</span>
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Page Content -->
        <main class="pl-64 pt-16 transition-all duration-300">
            <div class="container mx-auto px-4 py-6">
                @yield('content')
            </div>
        </main>

        @livewire('notifications.manager')
    </div>

    <script>
        // Theme toggle functionality
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }

        // Set initial theme from localStorage or system preference
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                document.documentElement.setAttribute('data-theme', savedTheme);
            } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }

            // Auto-hide flash messages after 5 seconds
            setTimeout(() => {
                const flashMessage = document.querySelector('.animate-fade-in-down');
                if (flashMessage) {
                    flashMessage.remove();
                }
            }, 5000);
        });

        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.querySelector('#layout-sidebar');
            const navbar = document.querySelector('#main-navbar');
            const main = document.querySelector('main');
            const sidebarToggles = document.querySelectorAll('[aria-label="Toggle sidebar"]');
            
            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                main.classList.toggle('pl-64');
                main.classList.toggle('pl-0');
                navbar.classList.toggle('left-64');
                navbar.classList.toggle('left-0');
            }

            sidebarToggles.forEach(toggle => {
                toggle.addEventListener('click', toggleSidebar);
            });

            // Handle responsive behavior
            const mediaQuery = window.matchMedia('(max-width: 1024px)');
            function handleResponsive(e) {
                if (e.matches) {
                    sidebar.classList.add('-translate-x-full');
                    main.classList.remove('pl-64');
                    main.classList.add('pl-0');
                    navbar.classList.remove('left-64');
                    navbar.classList.add('left-0');
                } else {
                    sidebar.classList.remove('-translate-x-full');
                    main.classList.add('pl-64');
                    main.classList.remove('pl-0');
                    navbar.classList.add('left-64');
                    navbar.classList.remove('left-0');
                }
            }
            
            mediaQuery.addListener(handleResponsive);
            handleResponsive(mediaQuery);
        });

        // Page Loader
        document.addEventListener('DOMContentLoaded', () => {
            const loader = document.getElementById('page-loader');
            
            // Hide loader when page is fully loaded
            window.addEventListener('load', () => {
                loader.classList.add('loader-hidden');
            });

            // Show loader during page transitions
            document.addEventListener('livewire:navigating', () => {
                loader.classList.remove('loader-hidden');
            });

            // Handle Livewire navigation events
            document.addEventListener('livewire:navigated', () => {
                // Keep loader visible for a minimum time to ensure smooth transition
                setTimeout(() => {
                    loader.classList.add('loader-hidden');
                }, 500);
            });

            // Handle Livewire loading states
            document.addEventListener('livewire:load', () => {
                loader.classList.remove('loader-hidden');
            });

            document.addEventListener('livewire:initialized', () => {
                setTimeout(() => {
                    loader.classList.add('loader-hidden');
                }, 500);
            });

            // Handle Livewire updates
            document.addEventListener('livewire:update', () => {
                loader.classList.remove('loader-hidden');
            });

            document.addEventListener('livewire:updated', () => {
                setTimeout(() => {
                    loader.classList.add('loader-hidden');
                }, 500);
            });
        });
    </script>
    
    @stack('scripts')
</body>

</html>
