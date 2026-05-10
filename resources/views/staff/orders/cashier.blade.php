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
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl flex items-center justify-between gap-3" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                @if(session('print_receipt'))
                    <a href="{{ route('staff.orders.receipt', session('print_receipt')) }}" target="_blank"
                       class="shrink-0 text-sm font-bold text-emerald-700 underline hover:text-emerald-900 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Reprint Receipt
                    </a>
                @endif
            </div>
            {{-- Auto-open receipt tab on payment confirmation --}}
            @if(session('print_receipt'))
                <script>
                    window.open('{{ route('staff.orders.receipt', session('print_receipt')) }}', '_blank');
                </script>
            @endif
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
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-50 p-6 flex flex-col gap-4 hover:shadow-md transition-all relative overflow-hidden"
                         x-data="{
                            splitMode: false,
                            items: [
                                @foreach($order['items'] as $index => $item)
                                {
                                    index: {{ $index }},
                                    name: '{{ addslashes($item['name']) }}',
                                    price: {{ (float)($item['price'] ?? 0) }},
                                    orderedQty: {{ (int)($item['quantity'] ?? 1) }},
                                    paidQty: {{ (int)($item['paid_quantity'] ?? 0) }},
                                    payQty: {{ max(0, (int)($item['quantity'] ?? 1) - (int)($item['paid_quantity'] ?? 0)) }}
                                },
                                @endforeach
                            ],
                            get amountPaidAlready() {
                                return this.items.reduce((sum, item) => sum + (item.paidQty * item.price), 0);
                            },
                            get amountRemaining() {
                                return {{ (float)$order['total_amount'] }} - this.amountPaidAlready;
                            },
                            get splitTotal() {
                                return this.items.reduce((sum, item) => sum + (item.payQty * item.price), 0);
                            },
                            get hasValidSplit() {
                                return this.splitTotal > 0 && this.items.every(i => i.payQty >= 0 && (i.paidQty + i.payQty) <= i.orderedQty);
                            }
                         }"
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
                            <div class="flex flex-col items-end gap-1">
                                <span class="text-[10px] font-bold text-emerald-600 px-3 py-1 bg-emerald-50 rounded-full">Completed</span>
                                @if(($order['payment_status'] ?? 'unpaid') === 'partially_paid')
                                    <span class="text-[10px] font-bold text-amber-600 px-3 py-1 bg-amber-50 rounded-full">Partially Paid</span>
                                @endif
                            </div>
                        </div>

                        {{-- Order Items (Normal Mode) --}}
                        <div class="bg-gray-50 rounded-2xl p-4 space-y-2 flex-grow" x-show="!splitMode">
                            <template x-for="item in items" :key="item.index">
                                <div class="flex justify-between items-center text-sm" :class="item.orderedQty === item.paidQty ? 'opacity-40 line-through' : ''">
                                    <span class="text-gray-700 font-medium">
                                        <span class="font-black text-gray-900" x-text="item.orderedQty + 'x'"></span>
                                        <span x-text="item.name"></span>
                                        <span x-show="item.paidQty > 0 && item.paidQty < item.orderedQty" class="text-xs text-amber-600 font-bold ml-1">(Paid: <span x-text="item.paidQty"></span>)</span>
                                    </span>
                                    <span class="text-gray-500 font-medium">RM <span x-text="(item.price * item.orderedQty).toFixed(2)"></span></span>
                                </div>
                            </template>
                        </div>

                        {{-- Order Items (Split Mode) --}}
                        <div class="bg-blue-50 rounded-2xl p-4 space-y-3 flex-grow" x-show="splitMode" style="display: none;">
                            <div class="text-xs font-bold text-blue-800 uppercase tracking-widest mb-2 flex justify-between">
                                <span>Select Items to Pay</span>
                                <span class="text-amber-600" x-text="'RM ' + amountRemaining.toFixed(2) + ' Pending'"></span>
                            </div>
                            <template x-for="item in items" :key="item.index">
                                <div class="flex justify-between items-center text-sm" x-show="item.orderedQty > item.paidQty">
                                    <div class="flex flex-col">
                                        <span class="text-gray-900 font-bold" x-text="item.name"></span>
                                        <span class="text-gray-500 text-xs">RM <span x-text="item.price.toFixed(2)"></span> each (Unpaid: <span x-text="item.orderedQty - item.paidQty"></span>)</span>
                                    </div>
                                    <div class="flex items-center gap-2 bg-white rounded-lg p-1 border border-gray-200">
                                        <button type="button" @click="if(item.payQty > 0) item.payQty--" class="w-6 h-6 rounded bg-gray-100 text-gray-600 flex items-center justify-center font-bold hover:bg-gray-200">-</button>
                                        <span class="w-4 text-center font-bold text-gray-900" x-text="item.payQty"></span>
                                        <button type="button" @click="if(item.payQty < (item.orderedQty - item.paidQty)) item.payQty++" class="w-6 h-6 rounded bg-blue-100 text-blue-600 flex items-center justify-center font-bold hover:bg-blue-200">+</button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Total --}}
                        <div class="flex justify-between items-center border-t border-gray-100 pt-4" x-show="!splitMode">
                            <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Total Remaining</span>
                            <span class="text-2xl font-black text-gray-900">RM <span x-text="amountRemaining.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between items-center border-t border-blue-100 pt-4" x-show="splitMode" style="display: none;">
                            <span class="text-sm font-bold text-blue-600 uppercase tracking-widest">Paying Now</span>
                            <span class="text-2xl font-black text-blue-700">RM <span x-text="splitTotal.toFixed(2)"></span></span>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-col gap-2 mt-2">
                            <div class="flex gap-2" x-show="!splitMode">
                                <a href="{{ route('staff.orders.receipt', $order['reference']) }}" target="_blank"
                                   class="flex-1 border-2 border-gray-200 hover:border-gray-400 text-gray-600 hover:text-gray-900 font-bold py-3 px-2 rounded-2xl transition-all flex items-center justify-center gap-2 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                    </svg>
                                    Receipt
                                </a>
                                <button type="button" @click="splitMode = true"
                                   class="flex-1 border-2 border-blue-200 hover:border-blue-400 text-blue-600 hover:text-blue-900 font-bold py-3 px-2 rounded-2xl transition-all flex items-center justify-center gap-2 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                    Split Pay
                                </button>
                            </div>
                            
                            <div class="flex gap-2" x-show="splitMode" style="display: none;">
                                <button type="button" @click="splitMode = false"
                                   class="flex-1 border-2 border-gray-200 text-gray-600 hover:bg-gray-50 font-bold py-3 px-2 rounded-2xl transition-all text-sm">
                                    Cancel Split
                                </button>
                            </div>

                            <form x-show="!splitMode" action="{{ route('staff.orders.markAsPaid', $order['reference']) }}" method="POST"
                                  onsubmit="return confirm('Confirm FULL payment of RM ' + amountRemaining.toFixed(2) + ' for Order #{{ $order['reference'] }}?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black py-4 px-6 rounded-2xl shadow-lg shadow-emerald-500/20 transition-all transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Pay Remaining
                                </button>
                            </form>

                            <form x-show="splitMode" style="display: none;" action="{{ route('staff.orders.payPartial', $order['reference']) }}" method="POST"
                                  @submit.prevent="if(confirm('Confirm partial payment of RM ' + splitTotal.toFixed(2) + ' for Order #{{ $order['reference'] }}?')) $el.submit()">
                                @csrf
                                @method('PATCH')
                                <template x-for="item in items" :key="item.index">
                                    <input type="hidden" :name="'items['+item.index+']'" :value="item.payQty">
                                </template>
                                <button type="submit" :disabled="!hasValidSplit"
                                    :class="hasValidSplit ? 'bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/20 transform hover:scale-[1.02] active:scale-95' : 'bg-gray-300 cursor-not-allowed'"
                                    class="w-full text-white font-black py-4 px-6 rounded-2xl transition-all flex items-center justify-center gap-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Confirm Split Payment
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-admin-layout>
