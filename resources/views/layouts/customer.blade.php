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

    <!-- Bottom Navigation Bar + Orders Slide-up Drawer -->
    <div id="orders-nav-root"
         x-data="orderTrackerNav()"
         x-init="init()"
         @orders-updated.window="refreshOrders()">

        <!-- Backdrop -->
        <div x-show="drawerOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="drawerOpen = false"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[55]">
        </div>

        <!-- Orders Drawer Panel -->
        <div x-show="drawerOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-6"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-6"
             class="fixed bottom-16 left-0 right-0 z-[60] bg-white dark:bg-gray-900 rounded-t-3xl shadow-2xl max-h-[72vh] overflow-y-auto border-t border-gray-100 dark:border-gray-800 transition-colors duration-300">

            <!-- Drag Handle -->
            <div class="flex justify-center pt-3 pb-1 sticky top-0 bg-white dark:bg-gray-900 rounded-t-3xl z-10 transition-colors">
                <div class="w-10 h-1 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
            </div>

            <!-- Drawer Header -->
            <div class="px-5 py-3 flex items-center justify-between border-b border-gray-100 dark:border-gray-800 sticky top-5 bg-white dark:bg-gray-900 z-10 transition-colors">
                <h2 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    My Orders
                </h2>
                <div class="flex items-center gap-3">
                    @auth
                    <a href="{{ route('customer.profile') }}"
                       @click="drawerOpen = false"
                       class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline transition-colors">
                        Full History &rarr;
                    </a>
                    @endauth
                    <button @click="drawerOpen = false"
                            class="p-1 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Orders List -->
            <div class="px-4 py-3 space-y-3 pb-8">

                <!-- Empty State -->
                <template x-if="orders.length === 0">
                    <div class="text-center py-10">
                        <svg class="w-14 h-14 text-gray-200 dark:text-gray-700 mx-auto mb-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-gray-600 dark:text-gray-400 font-bold text-sm transition-colors">No orders tracked yet</p>
                        <p class="text-gray-400 dark:text-gray-500 text-xs mt-1 transition-colors">Place an order and it will appear here automatically.</p>
                    </div>
                </template>

                <!-- Order Cards -->
                <template x-for="order in orders" :key="order.reference">
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-4 flex items-center gap-3 border border-gray-100 dark:border-gray-700 transition-colors">

                        <!-- Colored status dot -->
                        <div class="w-2.5 h-2.5 rounded-full flex-shrink-0 transition-colors"
                             :class="{
                                 'bg-blue-400': order.status === 'submitted',
                                 'bg-amber-400 animate-pulse': order.status === 'in_progress',
                                 'bg-emerald-500': order.status === 'completed' && order.payment_status !== 'paid',
                                 'bg-gray-300 dark:bg-gray-600': order.payment_status === 'paid'
                             }">
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                <span class="font-mono font-black text-gray-900 dark:text-white text-sm transition-colors" x-text="'#' + order.reference"></span>
                                <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-full leading-none transition-colors"
                                      :class="{
                                          'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300': order.status === 'submitted',
                                          'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300': order.status === 'in_progress',
                                          'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300': order.status === 'completed' && order.payment_status !== 'paid',
                                          'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400': order.payment_status === 'paid'
                                      }"
                                      x-text="order.payment_status === 'paid' ? '\u2713 Paid' : order.status.replace('_', ' ')">
                                </span>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 font-medium transition-colors" x-text="'RM ' + parseFloat(order.total_amount || 0).toFixed(2)"></p>
                        </div>

                        <a :href="'/track/' + order.reference"
                           @click="drawerOpen = false"
                           class="flex-shrink-0 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition active:scale-95">
                            Track
                        </a>
                    </div>
                </template>

            </div>
        </div>

        <!-- Bottom Navigation Bar (5 tabs) -->
        <nav class="fixed bottom-0 w-full bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 pb-safe z-50 shadow-[0_-4px_20px_-10px_rgba(0,0,0,0.1)] transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-1">
                <div class="flex justify-between items-center h-16">

                    <!-- Menu -->
                    <a href="{{ route('customer.menu', session('table_number')) }}"
                       class="flex flex-col items-center justify-center flex-1 h-full space-y-1 {{ request()->routeIs('customer.menu') ? 'text-premium-brown dark:text-premium-brown-light' : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300' }} transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="text-[10px] font-bold tracking-wide">Menu</span>
                    </a>

                    <!-- Rewards -->
                    <a href="{{ route('customer.rewards') }}"
                       class="flex flex-col items-center justify-center flex-1 h-full space-y-1 {{ request()->routeIs('customer.rewards') ? 'text-premium-brown dark:text-premium-brown-light' : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300' }} transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                        </svg>
                        <span class="text-[10px] font-bold tracking-wide">Rewards</span>
                    </a>

                    <!-- Orders (new tab) -->
                    <button @click="drawerOpen = !drawerOpen"
                            class="flex flex-col items-center justify-center flex-1 h-full space-y-1 transition-colors"
                            :class="drawerOpen || {{ request()->routeIs('customer.track') ? 'true' : 'false' }} ? 'text-premium-brown dark:text-premium-brown-light' : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300'">
                        <div class="relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <!-- Badge: submitted/in_progress orders -->
                            <span x-cloak
                                  x-show="activeCount > 0"
                                  x-text="activeCount"
                                  class="absolute -top-1.5 -right-2.5 min-w-[16px] h-4 px-1 inline-flex items-center justify-center text-[9px] font-black text-white bg-rose-500 rounded-full border border-white dark:border-gray-900">
                            </span>
                            <!-- Pulse dot: completed but not yet paid -->
                            <span x-cloak
                                  x-show="activeCount === 0 && hasPulse"
                                  class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-orange-500 rounded-full animate-pulse border-2 border-white dark:border-gray-900">
                            </span>
                        </div>
                        <span class="text-[10px] font-bold tracking-wide">Orders</span>
                    </button>

                    <!-- Cart -->
                    <div class="flex flex-col items-center justify-center flex-1 h-full"
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
                        <a href="{{ route('customer.cart') }}" class="relative flex flex-col items-center justify-center space-y-1 {{ request()->routeIs('customer.cart') ? 'text-premium-brown dark:text-premium-brown-light' : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300' }} transition-colors">
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
                    <a href="{{ auth()->check() ? route('customer.profile') : route('customer.login') }}"
                       class="flex flex-col items-center justify-center flex-1 h-full space-y-1 {{ request()->routeIs('customer.profile') || request()->routeIs('customer.login') || request()->routeIs('customer.register') ? 'text-premium-brown dark:text-premium-brown-light' : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300' }} transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-[10px] font-bold tracking-wide">Profile</span>
                    </a>

                </div>
            </div>
        </nav>
    </div>

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
    <!-- Alpine.js: Order Tracker Navbar Component -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderTrackerNav', () => ({
                orders: [],
                drawerOpen: false,

                get activeCount() {
                    // Orders that are still being processed (not completed, not paid)
                    return this.orders.filter(o =>
                        o.payment_status !== 'paid' &&
                        (o.status === 'submitted' || o.status === 'in_progress')
                    ).length;
                },

                get hasPulse() {
                    // Orders that are completed but waiting for payment at counter
                    return this.orders.some(o =>
                        o.status === 'completed' && o.payment_status !== 'paid'
                    );
                },

                init() {
                    this.refreshOrders();
                    this.cleanupOldOrders();
                    // Keep in sync if another tab updates localStorage
                    window.addEventListener('storage', (e) => {
                        if (e.key === 'bossku_tracked_orders') this.refreshOrders();
                    });
                },

                refreshOrders() {
                    try {
                        this.orders = JSON.parse(localStorage.getItem('bossku_tracked_orders') || '[]');
                    } catch(e) {
                        this.orders = [];
                    }
                },

                cleanupOldOrders() {
                    // Remove paid orders older than 24 hours to keep the list tidy
                    try {
                        const cutoff = new Date(Date.now() - 24 * 60 * 60 * 1000).toISOString();
                        let orders = JSON.parse(localStorage.getItem('bossku_tracked_orders') || '[]');
                        orders = orders.filter(o => {
                            if (o.payment_status !== 'paid') return true;
                            return o.created_at && o.created_at > cutoff;
                        });
                        localStorage.setItem('bossku_tracked_orders', JSON.stringify(orders));
                        this.orders = orders;
                    } catch(e) {}
                }
            }));
        });
    </script>

    <!-- Global Firebase Listener: watches all tracked orders on every page -->
    <script type="module">
        import { initializeApp, getApps } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js";

        const STORAGE_KEY = 'bossku_tracked_orders';
        const FIREBASE_CONFIG = {
            apiKey: "AIzaSyCn5HX9ckfF1mOEO7YtFS9A4Ql1hP58rUw",
            authDomain: "bossku-web.firebaseapp.com",
            projectId: "bossku-web",
            storageBucket: "bossku-web.firebasestorage.app",
            messagingSenderId: "1057285262370",
            appId: "1:1057285262370:web:5337aab034df19510f0e7b",
            databaseURL: "https://bossku-web-default-rtdb.asia-southeast1.firebasedatabase.app"
        };

        function getTrackedOrders() {
            try { return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'); } catch(e) { return []; }
        }

        function updateLocalOrder(reference, status, payment_status) {
            try {
                let orders = getTrackedOrders();
                const idx = orders.findIndex(o => o.reference === reference);
                if (idx >= 0) {
                    orders[idx].status = status;
                    orders[idx].payment_status = payment_status;
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(orders));
                    window.dispatchEvent(new CustomEvent('orders-updated'));
                }
            } catch(e) {}
        }

        function playNotificationSound() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const playBeep = (freq, start, dur) => {
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.connect(gain); gain.connect(audioCtx.destination);
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(freq, start);
                    gain.gain.setValueAtTime(0.3, start);
                    gain.gain.exponentialRampToValueAtTime(0.001, start + dur);
                    osc.start(start); osc.stop(start + dur);
                };
                const now = audioCtx.currentTime;
                playBeep(523.25, now, 0.3);       // C5
                playBeep(659.25, now + 0.15, 0.4); // E5
            } catch(e) {}
        }

        function showNotification(title, body) {
            if ('Notification' in window && Notification.permission === 'granted') {
                try { new Notification(title, { body, icon: '/images/logo.png' }); } catch(e) {}
            }
        }

        // Only attach Firebase listeners for orders that haven't been paid yet
        const trackedOrders = getTrackedOrders().filter(o => o.payment_status !== 'paid');
        if (trackedOrders.length === 0) {
            // nothing to watch — exit early without loading Firebase
            throw new Error('No active orders to watch.'); // stops module execution cleanly
        }

        // Initialize a named Firebase app (avoids conflict with the track page's default app)
        let app;
        const existingApp = getApps().find(a => a.name === 'navbar-tracker');
        app = existingApp || initializeApp(FIREBASE_CONFIG, 'navbar-tracker');
        const database = getDatabase(app);

        // Request notification permission if not decided yet
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        // On the /track page the existing listener already handles sound — skip double-notification
        const isTrackPage = window.location.pathname.startsWith('/track/');

        // Track which orders have fired their initial (connection) Firebase event
        // so we don't trigger a notification on the very first snapshot
        const firstFireDone = new Set();

        trackedOrders.forEach(order => {
            const orderRef = ref(database, `orders/${order.reference}`);

            onValue(orderRef, (snapshot) => {
                const data = snapshot.val();
                if (!data) return;

                const newStatus = data.status;
                const newPayment = data.payment_status ?? 'unpaid';

                // Read the last known status from localStorage before we overwrite it
                const currentOrders = getTrackedOrders();
                const current = currentOrders.find(o => o.reference === order.reference);
                const lastStatus = current ? current.status : order.status;

                // Persist the new status to localStorage + notify Alpine
                updateLocalOrder(order.reference, newStatus, newPayment);

                // Determine if this is the very first Firebase event (initial load)
                const isInitialLoad = !firstFireDone.has(order.reference);
                firstFireDone.add(order.reference);

                // Play sound + vibrate + push notification only on real status change
                if (!isTrackPage && !isInitialLoad && newStatus === 'completed' && lastStatus !== 'completed') {
                    playNotificationSound();
                    if ('vibrate' in navigator) navigator.vibrate([200, 100, 200]);
                    showNotification(
                        'Order Complete! \uD83C\uDF7D\uFE0F',
                        `Your order #${order.reference} is ready! Please proceed to the counter.`
                    );
                }
            });
        });
    </script>
</body>

</html>