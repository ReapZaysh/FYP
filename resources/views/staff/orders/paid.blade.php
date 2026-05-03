<x-admin-layout>
    <x-slot name="header">
        <nav class="flex text-sm text-gray-500 gap-2 items-center">
            <a href="{{ route('dashboard') }}" class="hover:text-premium-brown">Dashboard</a>
            <span>/</span>
            <span class="text-premium-brown font-medium">Paid Orders</span>
        </nav>
    </x-slot>

    <div class="space-y-6" x-data="{ search: '' }">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="font-serif text-3xl text-gray-900">Paid Orders</h1>
                <p class="text-gray-500">View and print receipts for previously paid orders</p>
            </div>
        </div>

        {{-- Summary Card --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-50 p-6 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Total Paid Orders</p>
                <h3 class="text-3xl font-black text-gray-900">{{ $paidOrders->count() }}</h3>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Total Revenue (Paid)</p>
                <h3 class="text-3xl font-black text-emerald-500">RM {{ number_format($totalPaid, 2) }}</h3>
            </div>
        </div>

        {{-- Live Search Bar --}}
        <div class="relative">
            <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input
                x-model="search"
                type="text"
                placeholder="Search by table, reference, or customer name..."
                class="w-full pl-12 pr-10 py-4 bg-white border border-gray-100 rounded-2xl shadow-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-premium-brown/30 focus:border-premium-brown transition"
            >
            <template x-if="search !== ''">
                <button @click="search = ''" class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </template>
        </div>

        {{-- Order Cards --}}
        @if($paidOrders->isEmpty())
            <div class="p-16 bg-white rounded-3xl border-2 border-dashed border-gray-100 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <p class="text-gray-900 font-bold text-xl mb-1">No paid orders found</p>
                <p class="text-gray-400 text-sm">Once orders are marked as paid, they will appear here.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($paidOrders as $id => $order)
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-50 p-6 flex flex-col gap-4 hover:shadow-md transition-all"
                         x-show="search === '' || 
                                 '{{ strtolower($order['table_number'] ?? '') }}'.includes(search.toLowerCase()) || 
                                 '{{ strtolower($order['reference'] ?? '') }}'.includes(search.toLowerCase()) ||
                                 '{{ strtolower($order['customer_name'] ?? '') }}'.includes(search.toLowerCase()) ||
                                 ('table ' + '{{ strtolower($order['table_number'] ?? '') }}').includes(search.toLowerCase())"
                         x-transition>

                        {{-- Order Header --}}
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">#{{ $order['reference'] }}</span>
                                <h4 class="font-bold text-gray-900 text-lg leading-tight">{{ $order['customer_name'] ?? 'Guest Customer' }}</h4>
                                @if(!empty($order['table_number']))
                                    <span class="text-xs font-bold text-blue-500">Table {{ $order['table_number'] }}</span>
                                @endif
                                @if(!empty($order['paid_at']))
                                    <div class="flex items-center gap-1 mt-1">
                                        <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-[11px] text-emerald-600 font-bold">
                                            Paid {{ \Carbon\Carbon::parse($order['paid_at'])->setTimezone('Asia/Kuala_Lumpur')->diffForHumans() }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <span class="text-[10px] font-bold text-emerald-600 px-3 py-1 bg-emerald-50 rounded-full">Paid</span>
                        </div>

                        {{-- Order Items --}}
                        <div class="bg-gray-50 rounded-2xl p-4 space-y-2 flex-grow">
                            @foreach($order['items'] as $item)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-700 font-medium">
                                        <span class="font-black text-gray-900">{{ $item['quantity'] ?? 1 }}x</span>
                                        {{ $item['name'] }}
                                    </span>
                                    <span class="text-gray-500 font-medium">RM {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Total --}}
                        <div class="flex justify-between items-center border-t border-gray-100 pt-4">
                            <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Total Amount</span>
                            <span class="text-2xl font-black text-gray-900">RM {{ number_format($order['total_amount'], 2) }}</span>
                        </div>

                        {{-- Print Receipt Button --}}
                        <a href="{{ route('staff.orders.receipt', $order['reference']) }}" target="_blank"
                           class="w-full bg-gray-900 hover:bg-black text-white font-black py-4 px-6 rounded-2xl shadow-lg shadow-gray-900/20 transition-all transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print Receipt
                        </a>

                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-admin-layout>
