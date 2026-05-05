<x-customer-layout>
    <div class="max-w-7xl mx-auto px-4 py-8" 
         x-data="{ 
            status: '{{ $order['status'] }}',
            payment_status: '{{ $order['payment_status'] ?? 'unpaid' }}',
            statuses: ['submitted', 'in_progress', 'completed'],
            get currentStatusIndex() {
                return this.statuses.indexOf(this.status);
            }
         }"
         @status-updated.window="status = $event.detail.status; payment_status = $event.detail.payment_status">
        
        <div class="bg-white dark:bg-gray-900 shadow-lg rounded-lg overflow-hidden border border-gray-100 dark:border-gray-800 p-6 text-center transition-colors">
            <h2 class="text-3xl font-bold mb-2 text-gray-800 dark:text-white transition-colors">Order #{{ $order['reference'] }}</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-6 transition-colors">Thank you for your order!</p>

            <div class="flex justify-center items-center mb-8 space-x-4">
                <!-- Canceled State -->
                <template x-if="status === 'canceled'">
                    <div class="p-4 bg-red-100 text-red-700 rounded-lg w-full">
                        Order Canceled
                    </div>
                </template>

                <!-- Status Progress Bar -->
                <template x-if="status !== 'canceled'">
                    <div class="flex justify-between w-full max-w-md mx-auto">
                        <template x-for="(s, index) in statuses" :key="index">
                            <div class="flex flex-col items-center flex-1">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white mb-2 transition-all duration-500"
                                    :class="index <= currentStatusIndex ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-800'">
                                    <span x-text="index + 1"></span>
                                </div>
                                <span
                                    class="text-[10px] sm:text-sm font-semibold capitalize transition-all duration-500"
                                    :class="index <= currentStatusIndex ? 'text-green-600' : 'text-gray-400 dark:text-gray-600'"
                                    x-text="s.replace('_', ' ')">
                                </span>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <div class="text-left border-t dark:border-gray-800 pt-6 transition-colors">
                <h3 class="font-bold text-lg mb-4 dark:text-white transition-colors">Order Summary</h3>
                <ul class="space-y-4">
                    @foreach($order['items'] as $item)
                        <li class="border-b border-gray-50 dark:border-gray-800 pb-2 last:border-0 transition-colors">
                            <div class="flex justify-between items-start">
                                <div class="dark:text-gray-200">
                                    <span class="font-semibold">{{ $item['quantity'] }}x</span> {{ $item['name'] }}
                                    @if(!empty($item['note']))
                                        <p class="text-xs text-gray-400 dark:text-gray-500 italic mt-1 transition-colors">Note: {{ $item['note'] }}</p>
                                    @endif
                                </div>
                                <div class="font-semibold text-gray-700 dark:text-gray-300 text-sm transition-colors">RM {{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                @if(!empty($order['order_note']))
                    <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 text-sm transition-colors">
                        <p class="text-gray-400 dark:text-gray-500 font-bold uppercase tracking-widest text-[10px] mb-2">Your Instructions</p>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed transition-colors">{{ $order['order_note'] }}</p>
                    </div>
                @endif

                <div class="border-t dark:border-gray-800 mt-4 pt-4 flex justify-between items-center text-xl font-black text-gray-900 dark:text-white transition-colors">
                    <span>Total</span>
                    <span>RM {{ number_format($order['total_amount'], 2) }}</span>
                </div>
            </div>

            <div class="mt-8 flex flex-col items-center gap-4">

                {{-- Payment Status Banners --}}
                <template x-if="status === 'completed' && payment_status !== 'paid'">
                    <div class="w-full p-4 bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-900/30 rounded-2xl text-center transition-colors">
                        <p class="text-amber-700 dark:text-amber-400 font-bold text-lg">🍽️ Your food is ready!</p>
                        <p class="text-amber-600 dark:text-amber-500 text-sm mt-1">Please proceed to the counter to pay.</p>
                    </div>
                </template>
                <template x-if="payment_status === 'paid'">
                    <div class="w-full p-4 bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-200 dark:border-emerald-900/30 rounded-2xl text-center transition-colors">
                        <p class="text-emerald-700 dark:text-emerald-400 font-bold text-lg">✅ Payment Confirmed!</p>
                        <p class="text-emerald-600 dark:text-emerald-500 text-sm mt-1">Thank you for dining with us. See you again!</p>
                    </div>
                </template>

                <a href="{{ route('customer.menu', $order['table_number'] ?? null) }}" class="text-blue-500 hover:text-blue-800 font-semibold">Place New Order</a>
                
                <!-- Live Indicator -->
                <div id="connection-status" class="flex items-center gap-2 text-[10px] uppercase tracking-widest font-bold text-gray-400">
                    <div class="w-2 h-2 rounded-full bg-gray-300 animate-pulse" id="status-dot"></div>
                    <span id="status-text">Connecting...</span>
                </div>
            </div>

        </div>
    </div>

    {{-- Firebase Real-time Status listener script --}}
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js";

        // Firebase configuration (reused for client-side listening)
        const firebaseConfig = {
            apiKey: "AIzaSyCn5HX9ckfF1mOEO7YtFS9A4Ql1hP58rUw",
            authDomain: "bossku-web.firebaseapp.com",
            projectId: "bossku-web",
            storageBucket: "bossku-web.firebasestorage.app",
            messagingSenderId: "1057285262370",
            appId: "1:1057285262370:web:5337aab034df19510f0e7b",
            databaseURL: "https://bossku-web-default-rtdb.asia-southeast1.firebasedatabase.app"
        };

        // Initialize Firebase and get database reference
        const app = initializeApp(firebaseConfig);
        const database = getDatabase(app);
        const orderRef = ref(database, 'orders/{{ $order['reference'] }}'); // Listen only to this specific order
        const connectedRef = ref(database, '.info/connected');

        const statusDot = document.getElementById('status-dot');
        const statusText = document.getElementById('status-text');

        /**
         * Monitor connection state to the live server
         * Firebase provides a special '.info/connected' path to check if the client is currently connected
         */
        onValue(connectedRef, (snap) => {
            if (snap.val() === true) {
                statusDot.classList.remove('bg-gray-300', 'bg-red-500');
                statusDot.classList.add('bg-green-500');
                statusText.innerText = 'Live Updates Active';
            } else {
                statusDot.classList.remove('bg-green-500');
                statusDot.classList.add('bg-red-500');
                statusText.innerText = 'Connection Lost';
            }
        });

        /**
         * Monitor the status of this specific order
         * When it changes in Firebase, we update the Alpine.js state
         */
        onValue(orderRef, (snapshot) => {
            const data = snapshot.val();
            if (!data) return;

            // Dispatch a global event that Alpine.js is listening for (@status-updated.window)
            // This allows the UI to update smoothly without a full page reload
            window.dispatchEvent(new CustomEvent('status-updated', { 
                detail: { 
                    status: data.status,
                    payment_status: data.payment_status ?? 'unpaid'
                } 
            }));

            // If the status has actually changed from what's on the page, trigger a visual "ping" animation
            if (data.status !== '{{ $order['status'] }}') {
                console.log("Status update detected:", data.status);
                statusDot.classList.add('animate-ping');
                setTimeout(() => statusDot.classList.remove('animate-ping'), 2000);
            }
        });
    </script>
</x-customer-layout>