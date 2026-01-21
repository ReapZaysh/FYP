<x-customer-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-3xl font-black text-gray-900 mb-1">Your Order</h2>
                <p class="text-gray-500 font-medium">Review your items before checkout</p>
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
                        class="bg-white rounded-3xl shadow-sm border border-gray-100 p-4 flex items-center gap-4 transition hover:shadow-md">
                        <!-- Product Image -->
                        <div
                            class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100">
                            @if(!empty($details['image']))
                                <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}"
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
                            <h3 class="font-black text-gray-900 text-lg sm:text-xl truncate">{{ $details['name'] }}</h3>
                            <p class="text-blue-600 font-bold mb-2">RM {{ number_format($details['price'], 2) }}</p>

                            <!-- Simple Actions for Mobile/All -->
                            <div class="flex items-center gap-4">
                                <!-- Qty Controls -->
                                <div class="flex items-center bg-gray-100 rounded-xl p-1 border border-gray-200">
                                    <form action="{{ route('customer.cart.remove', $id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center text-gray-600 hover:text-red-500 font-bold text-xl transition active:scale-90">-</button>
                                    </form>
                                    <span class="w-6 text-center font-black text-gray-900">{{ $details['quantity'] }}</span>
                                    <form action="{{ route('customer.cart.add', $id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center text-gray-600 hover:text-blue-600 font-bold text-xl transition active:scale-90">+</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Subtotal (Right Align) -->
                        <div class="text-right flex-shrink-0">
                            <span class="block text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Subtotal</span>
                            <span class="block text-lg font-black text-gray-900">RM
                                {{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Total Card -->
            <div class="bg-gray-900 rounded-[2.5rem] shadow-2xl p-8 text-white mb-12">
                <div class="flex justify-between items-center mb-8 pb-8 border-b border-gray-800">
                    <div>
                        <span class="text-gray-400 font-bold uppercase tracking-widest text-xs block mb-2">Grand
                            Total</span>
                        <span class="text-4xl sm:text-5xl font-black text-white">RM {{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <!-- Checkout Form -->
                <form action="{{ route('customer.order.store') }}" method="POST">
                    @csrf
                    <div class="mb-8">
                        <label class="block text-gray-400 text-xs font-bold uppercase tracking-widest mb-3"
                            for="customer_name">Who's this for?</label>
                        <input
                            class="w-full bg-gray-800/50 border-2 border-gray-700 rounded-2xl py-4 px-6 text-white text-lg font-bold placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition"
                            id="customer_name" name="customer_name" type="text" placeholder="Enter your name">
                        <p class="text-gray-500 text-sm mt-3 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Optional but helps us call out your order!
                        </p>
                    </div>

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
            <div class="text-center py-24 bg-white rounded-[3rem] shadow-sm border border-gray-100 px-6">
                <div class="w-32 h-32 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-4">Your cart is feeling lonely</h3>
                <p class="text-gray-500 mb-10 max-w-sm mx-auto">Looks like you haven't added any of our delicious food yet.
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