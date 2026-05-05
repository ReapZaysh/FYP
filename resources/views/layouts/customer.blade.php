<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bossku House') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'premium-brown': '#9c6644',
                        'premium-brown-light': '#d4a373',
                    }
                }
            }
        }
    </script>
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-950 pb-20 transition-colors duration-300">
    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-900 shadow-sm sticky top-0 z-50 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between py-2">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('customer.menu', session('table_number')) }}" class="flex items-center gap-2">
                            <img src="{{ asset('images/logo.png') }}" alt="Bossku Logo"
                                class="h-[60px] w-auto rounded-lg">
                            <span class="font-bold text-xl text-gray-800 dark:text-white transition-colors">Bossku House</span>
                        </a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Dark Mode Toggle -->
                    <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 transition-all">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464a1 1 0 101.414-1.414l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.485a1 1 0 01-1.414 0l-.707-.707a1 1 0 011.414-1.414l.707.707a1 1 0 010 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    </button>

                    <div class="flex items-center" 
                         @php
                             $initialCount = 0;
                             if (session('cart')) {
                                 foreach (session('cart') as $item) {
                                     $initialCount += $item['quantity'];
                                 }
                             }
                         @endphp
                         x-data="{ count: {{ $initialCount }} }" 
                         @cart-updated.window="count = $event.detail.count">
                        <a href="{{ route('customer.cart') }}" class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span x-cloak x-show="count > 0" x-text="count"
                                class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <!-- Bottom Navigation Bar -->
    <nav class="fixed bottom-0 w-full bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 pb-safe z-50 shadow-[0_-4px_20px_-10px_rgba(0,0,0,0.1)] transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Menu -->
                <a href="{{ route('customer.menu', session('table_number')) }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('customer.menu') ? 'text-premium-brown dark:text-premium-brown-light' : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="text-[10px] font-bold tracking-wide">Menu</span>
                </a>

                <!-- Redemption (Rewards) -->
                <a href="{{ route('customer.rewards') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('customer.rewards') ? 'text-premium-brown dark:text-premium-brown-light' : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                    <span class="text-[10px] font-bold tracking-wide">Rewards</span>
                </a>

                <!-- Cart -->
                <div class="flex flex-col items-center justify-center w-full h-full" 
                     @php
                         $initialCount = 0;
                         if (session('cart')) {
                             foreach (session('cart') as $item) {
                                 $initialCount += $item['quantity'];
                             }
                         }
                     @endphp
                     x-data="{ count: {{ $initialCount }} }" 
                     @cart-updated.window="count = $event.detail.count">
                    <a href="{{ route('customer.cart') }}" class="relative flex flex-col items-center justify-center space-y-1 {{ request()->routeIs('customer.cart') ? 'text-premium-brown dark:text-premium-brown-light' : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300' }}">
                        <div class="relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span x-cloak x-show="count > 0" x-text="count" class="absolute -top-1 -right-2 inline-flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-rose-500 rounded-full border border-white"></span>
                        </div>
                        <span class="text-[10px] font-bold tracking-wide">Cart</span>
                    </a>
                </div>

                <!-- Profile -->
                <a href="{{ auth()->check() ? route('customer.profile') : route('customer.login') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('customer.profile') || request()->routeIs('customer.login') || request()->routeIs('customer.register') ? 'text-premium-brown dark:text-premium-brown-light' : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-[10px] font-bold tracking-wide">Profile</span>
                </a>
            </div>
        </div>
    </nav>

    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {
            // toggle icons inside button
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // if set via local storage previously
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }

            // if NOT set via local storage previously
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    </script>
</body>

</html>