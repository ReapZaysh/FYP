<x-customer-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors">Your Order</h2>
                <p class="text-gray-500 dark:text-gray-400 font-medium transition-colors">Review your items before checkout</p>
            </div>
            @if(count($cart) > 0)
                <form action="{{ route('customer.cart.clear') }}" method="POST"
                    onsubmit="return confirm('Clear all items from cart?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-red-500 hover:text-red-700 font-bold text-sm flex items-center gap-1 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        Clear All
                    </button>
                </form>
            @endif
        </div>

        @if(count($cart) > 0)
            <!-- Cart Items -->
            <div class="space-y-4 mb-10">
                @foreach($cart as $id => $details)
                    <div
                        class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 p-4 flex items-center gap-4 transition hover:shadow-md dark:hover:shadow-blue-900/10">
                        <!-- Product Image -->
                        <div
                            class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 bg-gray-50 dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-colors">
                            @if(!empty($details['image']))
                                <img src="{{ str_starts_with($details['image'], 'http') ? $details['image'] : asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 002-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="flex-grow min-w-0">
                            <h3 class="font-black text-gray-900 dark:text-white text-lg sm:text-xl truncate transition-colors">{{ $details['name'] }}</h3>
                            <p class="text-blue-600 dark:text-blue-400 font-bold mb-2 transition-colors">RM {{ number_format($details['price'], 2) }}</p>

                            <!-- Simple Actions for Mobile/All -->
                            <div class="flex items-center gap-4">
                                <!-- Qty Controls -->
                                <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded-xl p-1 border border-gray-200 dark:border-gray-700 transition-colors">
                                    <form action="{{ route('customer.cart.remove', $id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:text-red-500 font-bold text-xl transition active:scale-90">-</button>
                                    </form>
                                    <span class="w-6 text-center font-black text-gray-900 dark:text-white">{{ $details['quantity'] }}</span>
                                    <form action="{{ route('customer.cart.add', $id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 font-bold text-xl transition active:scale-90">+</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Per-item Note Input -->
                            <div class="mt-3">
                                <input type="text" 
                                    name="notes[{{ $id }}]" 
                                    form="order-form"
                                    placeholder="Add note (e.g. no onions, extra spicy)"
                                    class="w-full text-xs bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-lg py-2 px-3 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition"
                                >
                            </div>
                        </div>

                        <!-- Subtotal (Right Align) -->
                        <div class="text-right flex-shrink-0">
                            <span class="block text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-wider mb-1 transition-colors">Subtotal</span>
                            <span class="block text-lg font-black text-gray-900 dark:text-white transition-colors">RM
                                {{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Total Card -->
            <div class="bg-gray-900 rounded-[2.5rem] shadow-2xl p-8 text-white mb-12">
                <div class="flex justify-between items-center mb-8 pb-8 border-b border-gray-800">
                    <div>
                        <span class="text-gray-400 font-bold uppercase tracking-widest text-xs block mb-2">Grand Total</span>
                        <span class="text-4xl sm:text-5xl font-black text-white">RM {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-amber-400 font-bold uppercase tracking-widest text-[10px] block mb-1">You'll Earn</span>
                        <span class="text-2xl font-black text-amber-400 flex items-center justify-end gap-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ floor($total) }} Boss Points
                        </span>
                    </div>
                </div>

                <!-- Checkout Form -->
                <form action="{{ route('customer.order.store') }}" method="POST" id="order-form">
                    @csrf
                    
                    <div class="mb-6 bg-gray-800 rounded-2xl p-4 flex items-center justify-between border border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-500/20 text-blue-400 p-2 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <span class="text-gray-400 text-xs font-bold uppercase tracking-widest block">Ordering For</span>
                                <span class="text-white font-black text-lg">Table {{ $tableNumber }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <div class="flex justify-between items-end mb-3">
                                <label class="block text-gray-400 text-xs font-bold uppercase tracking-widest"
                                    for="customer_name">Who's this for?</label>
                                @guest
                                    <a href="{{ route('customer.login', ['redirect' => route('customer.cart')]) }}" class="text-blue-400 text-xs font-bold hover:text-blue-300 transition">
                                        Login to earn points &rarr;
                                    </a>
                                @else
                                    <span class="text-emerald-400 text-xs font-bold flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Earning Points
                                    </span>
                                @endguest
                            </div>
                            <input
                                class="w-full bg-gray-800/50 border-2 border-gray-700 rounded-2xl py-4 px-6 text-white text-lg font-bold placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition"
                                id="customer_name" name="customer_name" type="text" 
                                placeholder="Enter your name (Optional)"
                                value="{{ auth()->check() ? auth()->user()->name : '' }}"
                                {{ auth()->check() ? 'readonly' : '' }}>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-xs font-bold uppercase tracking-widest mb-3"
                                for="order_note">Extra Information (Optional)</label>
                            <textarea
                                class="w-full bg-gray-800/50 border-2 border-gray-700 rounded-2xl py-3 px-6 text-white text-base font-medium placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition resize-none"
                                id="order_note" name="order_note" rows="2" placeholder="e.g. Bring drinks first, Happy Birthday!"></textarea>
                        </div>
                    </div>

                    @auth
                        @if($vouchers->count() > 0)
                        <div class="mb-8 p-5 bg-emerald-500/10 border-2 border-emerald-500/30 rounded-2xl">
                            <label class="block text-emerald-400 text-xs font-bold uppercase tracking-widest mb-3 flex items-center gap-2" for="voucher_id">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                Apply a Reward Voucher
                            </label>
                            <select id="voucher_id" name="voucher_id" class="w-full bg-gray-800/50 border-2 border-gray-700 rounded-xl py-3 px-4 text-white text-base font-medium focus:outline-none focus:border-emerald-500 transition appearance-none">
                                <option value="">-- Don't use a voucher this time --</option>
                                @foreach($vouchers as $voucher)
                                    <option value="{{ $voucher['id'] }}">{{ $voucher['reward_name'] }} (Redeemed {{ \Carbon\Carbon::parse($voucher['created_at'])->format('d M') }})</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    @endauth

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('customer.menu') }}"
                            class="flex-1 bg-gray-800 hover:bg-gray-700 text-white py-5 px-8 rounded-2xl text-lg font-black text-center transition active:scale-95">
                            Keep Shopping
                        </a>
                        <button type="submit"
                            class="flex-[2] bg-blue-600 hover:bg-blue-500 text-white py-5 px-8 rounded-2xl text-lg font-black shadow-xl shadow-blue-500/20 transition transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3">
                            Confirm Order
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="text-center py-24 bg-white dark:bg-gray-900 rounded-[3rem] shadow-sm border border-gray-100 dark:border-gray-800 px-6 transition-colors duration-300">
                <div class="w-32 h-32 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-8 transition-colors">
                    <svg class="w-16 h-16 text-gray-200 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-4 transition-colors">Your cart is feeling lonely</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-10 max-w-sm mx-auto transition-colors">Looks like you haven't added any of our delicious food yet.
                    Let's fix that!</p>
                <a href="{{ route('customer.menu') }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-black py-5 px-12 rounded-2xl shadow-xl shadow-blue-500/10 transition transform hover:scale-105 active:scale-95">
                    Browse Menu
                </a>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-gray-900/95 backdrop-blur text-white px-8 py-4 rounded-full shadow-2xl z-50 flex items-center gap-3 border border-gray-800">
            <div class="bg-green-500 rounded-full p-1">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif
</x-customer-layout>