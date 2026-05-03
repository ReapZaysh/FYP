<div x-show="sidebarOpen" class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden" @click="sidebarOpen = false" x-cloak x-transition:fade></div>

<aside 
    class="bg-white border-r border-gray-100 flex flex-col transition-all duration-300 ease-in-out fixed inset-y-0 left-0 z-50 lg:sticky lg:top-0 lg:h-screen shadow-sm"
    :class="sidebarOpen ? 'w-64 translate-x-0' : 'w-20 -translate-x-full lg:translate-x-0'"
>
    <!-- Brand -->
    <div class="h-24 flex items-center px-6 border-b border-gray-50 overflow-hidden shrink-0">
        <div class="flex items-center gap-3 transition-all duration-300" :class="!sidebarOpen && 'lg:justify-center lg:w-full'">
            <img src="{{ asset('images/logo.png') }}" class="w-10 h-10 shrink-0" alt="Logo">
            <span class="font-serif text-2xl tracking-tight text-gray-900 whitespace-nowrap overflow-hidden" 
                  x-show="sidebarOpen" x-transition:enter.duration.300ms>BOSSKU</span>
        </div>
    </div>

    <!-- Navigation Menu (Dynamically filtered by user role) -->
    <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
        @if(Auth::user()->role === 'admin')
            {{-- Admin Menu: Focuses on management, products, and analytics --}}
            <x-sidebar-item href="{{ route('dashboard') }}" icon="dashboard" label="Dashboard" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard')" />
            <x-sidebar-item href="{{ route('admin.categories.index') }}" icon="categories" label="Categories" :active="request()->routeIs('admin.categories.*')" />
            <x-sidebar-item href="{{ route('admin.products.index') }}" icon="products" label="Products" :active="request()->routeIs('admin.products.*')" />
            <x-sidebar-item href="{{ route('admin.analytics') }}" icon="analytics" label="Analytics" :active="request()->routeIs('admin.analytics')" />
            <x-sidebar-item href="{{ route('staff.orders.paid') }}" icon="orders" label="Paid Orders" :active="request()->routeIs('staff.orders.paid')" />
            <x-sidebar-item href="{{ route('staff.orders.history') }}" icon="history" label="Order History" :active="request()->routeIs('staff.orders.history')" />
            <x-sidebar-item href="{{ route('admin.reviews.index') }}" icon="reviews" label="Reviews" :active="request()->routeIs('admin.reviews.*')" />
        @else
            {{-- Staff Menu: Focused primarily on real-time order management --}}
            <x-sidebar-item href="{{ route('staff.orders.index') }}" icon="dashboard" label="Dashboard" :active="request()->routeIs('staff.orders.index')" />
            <x-sidebar-item href="{{ route('staff.orders.cashier') }}" icon="cashier" label="Cashier" :active="request()->routeIs('staff.orders.cashier')" />
            <x-sidebar-item href="{{ route('staff.orders.paid') }}" icon="orders" label="Paid Orders" :active="request()->routeIs('staff.orders.paid')" />
            <x-sidebar-item href="{{ route('staff.orders.history') }}" icon="history" label="Order History" :active="request()->routeIs('staff.orders.history')" />
        @endif
    </nav>

    <!-- User Section -->
    <div class="p-3 border-t border-gray-50 bg-gray-50/50">
        <div class="flex items-center gap-3 p-2 bg-white rounded-2xl border border-gray-100 transition-all duration-300" :class="!sidebarOpen && 'lg:justify-center lg:border-transparent lg:bg-transparent'">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" class="w-10 h-10 rounded-xl shrink-0">
            <div class="flex-1 min-w-0" x-show="sidebarOpen" x-transition:enter.duration.300ms>
                <p class="text-xs font-black text-gray-900 truncate uppercase tracking-tighter">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-gray-400 font-bold truncate uppercase">{{ Auth::user()->role }}</p>
            </div>
        </div>
        
        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" 
                class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all group"
                :class="!sidebarOpen && 'lg:px-2 lg:justify-center'"
            >
                <svg class="w-5 h-5 group-hover:scale-110 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span x-show="sidebarOpen" x-transition:enter.duration.300ms class="font-medium">Sign Out</span>
            </button>
        </form>
    </div>
</aside>
