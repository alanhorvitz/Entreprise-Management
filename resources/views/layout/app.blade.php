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
     
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
</head>

<body class="font-sans antialiased bg-base-100/50">
    <div class="min-h-screen">
        @include('layout.sidebar')
        @include('layout.navbar')

        <!-- Page Content -->
        <main class="pl-64 pt-16 transition-all duration-300">
            <div class="container mx-auto px-4 py-6">
                @yield('content')
            </div>
        </main>
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
    </script>
    
    <livewire:notification-manager />
    @stack('scripts')
</body>

</html>
