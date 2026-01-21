<x-customer-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-100 p-6 text-center">

            <h2 class="text-3xl font-bold mb-2 text-gray-800">Order #{{ $order['reference'] }}</h2>
            <p class="text-gray-500 mb-6">Thank you for your order!</p>

            <div class="flex justify-center items-center mb-8 space-x-4">
                @php
                    $statuses = ['submitted', 'in_progress', 'completed'];
                    $currentStatusIndex = array_search($order['status'], $statuses);
                    $canceled = $order['status'] === 'canceled';
                @endphp

                @if($canceled)
                    <div class="p-4 bg-red-100 text-red-700 rounded-lg w-full">
                        Order Canceled
                    </div>
                @else
                    @foreach($statuses as $index => $status)
                        <div class="flex flex-col items-center w-1/3">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white mb-2 
                                                                {{ $index <= $currentStatusIndex ? 'bg-green-500' : 'bg-gray-300' }}">
                                {{ $index + 1 }}
                            </div>
                            <span
                                class="text-sm font-semibold capitalize {{ $index <= $currentStatusIndex ? 'text-green-600' : 'text-gray-400' }}">
                                {{ str_replace('_', ' ', $status) }}
                            </span>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="text-left border-t pt-6">
                <h3 class="font-bold text-lg mb-4">Order Summary</h3>
                <ul class="space-y-4">
                    @foreach($order['items'] as $item)
                        <li class="flex justify-between">
                            <div>
                                <span class="font-semibold">{{ $item['quantity'] }}x</span> {{ $item['name'] }}
                            </div>
                            <div class="font-semibold">RM {{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                        </li>
                    @endforeach
                </ul>
                <div class="border-t mt-4 pt-4 flex justify-between items-center text-xl font-bold">
                    <span>Total</span>
                    <span>RM {{ number_format($order['total_amount'], 2) }}</span>
                </div>
            </div>

            <div class="mt-8">
                <a href="{{ route('customer.menu') }}" class="text-blue-500 hover:text-blue-800 font-semibold">Place New
                    Order</a>
            </div>

        </div>
    </div>

    <!-- Firebase Realtime Status listener -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js";

        const firebaseConfig = {
            apiKey: "AIzaSyCn5HX9ckfF1mOEO7YtFS9A4Ql1hP58rUw",
            authDomain: "bossku-web.firebaseapp.com",
            projectId: "bossku-web",
            storageBucket: "bossku-web.firebasestorage.app",
            messagingSenderId: "1057285262370",
            appId: "1:1057285262370:web:5337aab034df19510f0e7b",
            databaseURL: "https://bossku-web-default-rtdb.asia-southeast1.firebasedatabase.app"
        };

        const app = initializeApp(firebaseConfig);
        const database = getDatabase(app);
        const orderRef = ref(database, 'orders/{{ $order['reference'] }}');

        onValue(orderRef, (snapshot) => {
            const data = snapshot.val();
            if (data && data.status !== '{{ $order['status'] }}') {
                window.location.reload();
            }
        });
    </script>
</x-customer-layout>