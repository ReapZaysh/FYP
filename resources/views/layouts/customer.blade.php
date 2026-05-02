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

    <!-- Bottom Tab Bar for Mobile? Optional. For now, sticky nav is enough. -->
</body>

</html>