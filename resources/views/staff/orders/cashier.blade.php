<x-admin-layout>
    <x-slot name="header">
        <nav class="flex text-sm text-gray-500 gap-2 items-center">
            <a href="{{ route('dashboard') }}" class="hover:text-premium-brown">Dashboard</a>
            <span>/</span>
            <span class="text-premium-brown font-medium">Cashier</span>
        </nav>
    </x-slot>

    <div class="space-y-6" x-data="{ search: '' }">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="font-serif text-3xl text-gray-900">Cashier</h1>
                <p class="text-gray-500">Confirm customer payments for completed orders</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-3" role="alert">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Summary Card --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-50 p-6 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Awaiting Payment</p>
                <h3 class="text-3xl font-black text-gray-900">{{ $pendingPayment->count() }} Order{{ $pendingPayment->count() !== 1 ? 's' : '' }}</h3>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Total Pending</p>
                <h3 class="text-3xl font-black text-amber-500">RM {{ number_format($totalPending, 2) }}</h3>
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
                placeholder="Search by table number or reference (e.g. Table 3, XYA92B)..."
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
        @if($pendingPayment->isEmpty())
            <div class="p-16 bg-white rounded-3xl border-2 border-dashed border-gray-100 text-center">
                <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <p class="text-gray-900 font-bold text-xl mb-1">All caught up!</p>
                <p class="text-gray-400 text-sm">No orders are waiting for payment right now.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($pendingPayment as $id => $order)
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-50 p-6 flex flex-col gap-4 hover:shadow-md transition-all"
                         x-show="search === '' || 
                                 '{{ strtolower($order['table_number'] ?? '') }}'.includes(search.toLowerCase()) || 
                                 '{{ strtolower($order['reference'] ?? '') }}'.includes(search.toLowerCase()) ||
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
                                @if(!empty($order['created_at']))
                                    <div class="flex items-center gap-1 mt-1">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-[11px] text-gray-400 font-medium" title="{{ \Carbon\Carbon::parse($order['created_at'])->setTimezone('Asia/Kuala_Lumpur')->format('d M Y, h:i A') }}">
                                            {{ \Carbon\Carbon::parse($order['created_at'])->setTimezone('Asia/Kuala_Lumpur')->diffForHumans() }}
                                            &bull; {{ \Carbon\Carbon::parse($order['created_at'])->setTimezone('Asia/Kuala_Lumpur')->format('h:i A') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <span class="text-[10px] font-bold text-emerald-600 px-3 py-1 bg-emerald-50 rounded-full">Completed</span>
                        </div>

                        {{-- Order Items --}}
                        <div class="bg-gray-50 rounded-2xl p-4 space-y-2 flex-grow">
                            @foreach($order['items'] as $item)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-700 font-medium">
                                        <span class="font-black text-gray-900">{{ $item['quantity'] }}x</span>
                                        {{ $item['name'] }}
                                    </span>
                                    <span class="text-gray-500 font-medium">RM {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Total --}}
                        <div class="flex justify-between items-center border-t border-gray-100 pt-4">
                            <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Total</span>
                            <span class="text-2xl font-black text-gray-900">RM {{ number_format($order['total_amount'], 2) }}</span>
                        </div>

                        {{-- Mark as Paid Button --}}
                        <form action="{{ route('staff.orders.markAsPaid', $order['reference']) }}" method="POST"
                              onsubmit="return confirm('Confirm payment of RM {{ number_format($order['total_amount'], 2) }} for Order #{{ $order['reference'] }}?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black py-4 px-6 rounded-2xl shadow-lg shadow-emerald-500/20 transition-all transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Mark as Paid
                            </button>
                        </form>

                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-admin-layout>
