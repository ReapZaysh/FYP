<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bossku House') }} {{ ucfirst(Auth::user()->role) }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f9f7f4; }
        .font-serif { font-family: 'Playfair Display', serif; }
        .sidebar-item-active { background-color: #9c6644; color: white; }
        .bg-premium-brown { background-color: #9c6644; }
        .text-premium-brown { color: #9c6644; }
        .border-premium-brown { border-color: #9c6644; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="antialiased text-gray-800">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: true }">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Nav -->
            <header class="h-20 flex items-center justify-between px-8 bg-transparent">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 hover:bg-gray-200 rounded-lg lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div>
                        @if(isset($header))
                            {{ $header }}
                        @else
                            <nav class="flex text-sm text-gray-500 gap-2 items-center">
                                <span class="text-premium-brown font-medium">Dashboard</span>
                                <span>/</span>
                                <span>Overview</span>
                            </nav>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-6">

                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                            <div class="text-right hidden sm:block">
                                <div class="text-sm font-semibold">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</div>
                            </div>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 px-8 pb-8 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
