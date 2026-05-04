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
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100 pb-20">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between py-2">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('customer.menu') }}" class="flex items-center gap-2">
                            <img src="{{ asset('images/logo.png') }}" alt="Bossku Logo"
                                class="h-[60px] w-auto rounded-lg">
                            <span class="font-bold text-xl text-gray-800">Bossku House</span>
                        </a>
                    </div>
                </div>
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
                    <a href="{{ route('customer.cart') }}" class="relative p-2 text-gray-500 hover:text-gray-700">
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
    </nav>

    <main>
        {{ $slot }}
    </main>

    <!-- Bottom Navigation Bar -->
    <nav class="fixed bottom-0 w-full bg-white border-t border-gray-100 pb-safe z-50 shadow-[0_-4px_20px_-10px_rgba(0,0,0,0.1)]">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Menu (Default) -->
                <a href="{{ route('customer.menu') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('customer.menu') ? 'text-premium-brown' : 'text-gray-400 hover:text-gray-600' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="text-[10px] font-bold tracking-wide">Menu</span>
                </a>

                <!-- Redemption (Rewards) -->
                <a href="{{ route('customer.rewards') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('customer.rewards') ? 'text-premium-brown' : 'text-gray-400 hover:text-gray-600' }}">
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
                    <a href="{{ route('customer.cart') }}" class="relative flex flex-col items-center justify-center space-y-1 {{ request()->routeIs('customer.cart') ? 'text-premium-brown' : 'text-gray-400 hover:text-gray-600' }}">
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
                <a href="{{ route('customer.profile') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('customer.profile') ? 'text-premium-brown' : 'text-gray-400 hover:text-gray-600' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-[10px] font-bold tracking-wide">Profile</span>
                </a>
            </div>
        </div>
    </nav>
</body>

</html>