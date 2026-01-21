<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Bossku House</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,800" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 font-['Instrument_Sans'] antialiased min-h-screen flex flex-col items-center justify-center p-6">
    <header class="w-full max-w-5xl mb-12">
        <nav class="flex justify-end gap-6">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-semibold hover:text-blue-600 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold hover:text-blue-600 transition">Staff Login</a>
                @endauth
            @endif
        </nav>
    </header>

    <main class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-8">
            <div class="space-y-4">
                <h1 class="text-5xl lg:text-7xl font-black tracking-tight text-gray-950 leading-tight">
                    Bossku <span class="text-blue-600">House</span>
                </h1>
                <p class="text-xl text-gray-600 leading-relaxed max-w-lg">
                    Premium wings, refreshing drinks, and an effortless ordering experience. Scan the QR code at your table to begin your journey.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('customer.menu') }}" class="inline-flex items-center justify-center px-8 py-4 bg-gray-950 text-white rounded-2xl font-bold text-lg hover:bg-gray-800 transition-all shadow-xl hover:shadow-gray-200 active:scale-95 group">
                    View Menu
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-8 pt-8 border-t border-gray-200">
                <div>
                    <span class="block text-2xl font-bold text-gray-900">100%</span>
                    <span class="text-sm text-gray-500 uppercase tracking-widest font-semibold">Fresh Quality</span>
                </div>
                <div>
                    <span class="block text-2xl font-bold text-gray-900">Fast</span>
                    <span class="text-sm text-gray-500 uppercase tracking-widest font-semibold">Real-time Service</span>
                </div>
            </div>
        </div>

        <div class="relative">
            <div class="absolute -inset-4 bg-blue-100 rounded-[3rem] rotate-3 blur-2xl opacity-50"></div>
            <div class="relative bg-white p-4 rounded-[2.5rem] shadow-2xl border border-gray-100 overflow-hidden transform lg:rotate-2 hover:rotate-0 transition-transform duration-500">
                <img src="{{ asset('images/logo.png') }}" alt="Bossku House Logo" class="w-full h-auto rounded-[1.8rem]">
            </div>
        </div>
    </main>

    <footer class="mt-24 text-sm text-gray-400">
        &copy; {{ date('Y') }} Bossku House. All rights reserved.
    </footer>
</body>
</html>