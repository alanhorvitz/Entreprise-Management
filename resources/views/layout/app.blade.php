<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="stylesheet" href="{{ asset('supp-daisy.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Theme Toggle Script -->
    <script>
        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
        }

        // Theme toggle function
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }
    </script>
</head>

<body>
    <div id="root">
        <div class="size-full" id="layout-main">
            <div class="flex">
                <input id="layout-sidebar-toggle-trigger" class="hidden" aria-label="Toggle layout sidebar" type="checkbox" />
                @include('layout.sidebar')
                <label for="layout-sidebar-toggle-trigger" id="layout-sidebar-backdrop"></label>
                <div class="flex h-screen min-w-0 grow flex-col overflow-auto">
                    @include('layout.navbar')
                    <div id="layout-content">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium">@yield('title')</h3>
                            <div class="breadcrumbs hidden p-0 text-sm sm:inline">
                                <ul>
                                    <li><a href="/" data-discover="true">Dashboard</a></li>
                                    <li class="opacity-80">@yield('title')</li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-6">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

    <script>
        new MutationObserver(() => {
            document.querySelector("#splash-screen")?.classList.add("remove");
        }).observe(document.querySelector("#root"), {
            childList: true
        });
    </script>
</body>

</html>
