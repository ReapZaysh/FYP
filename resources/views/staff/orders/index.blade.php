<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Staff Order Dashboard') }}
            </h2>
            <div class="flex items-center gap-4">
                <a href="{{ route('staff.orders.history') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg transition border border-gray-200 text-sm">
                    View History
                </a>
                <button onclick="requestNotificationPermission()" id="notif-btn"
                    class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold py-2 px-4 rounded-lg transition border border-blue-200 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                    Enable Notifs
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="mb-6 bg-white rounded-lg shadow p-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800">Today's Sales</h3>
                <span class="text-2xl font-bold text-green-600">RM {{ number_format($today_sales, 2) }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Submitted Orders -->
                <div>
                    <h3 class="font-bold text-lg mb-4 text-blue-600 bg-blue-100 p-2 rounded">New Orders (Submitted)</h3>
                    @foreach($orders->where('status', 'submitted') as $id => $order)
                        @include('staff.orders.card', ['order' => $order, 'reference' => $id])
                    @endforeach
                    @if($orders->where('status', 'submitted')->isEmpty())
                        <p class="text-gray-500 text-sm">No new orders.</p>
                    @endif
                </div>

                <!-- In Progress Orders -->
                <div>
                    <h3 class="font-bold text-lg mb-4 text-yellow-600 bg-yellow-100 p-2 rounded">In Progress</h3>
                    @foreach($orders->where('status', 'in_progress') as $id => $order)
                        @include('staff.orders.card', ['order' => $order, 'reference' => $id])
                    @endforeach
                    @if($orders->where('status', 'in_progress')->isEmpty())
                        <p class="text-gray-500 text-sm">No orders in progress.</p>
                    @endif
                </div>

                <!-- Completed (Recent) -->
                <div>
                    <div class="flex justify-between items-center mb-4 text-green-600 bg-green-100 p-2 rounded">
                        <h3 class="font-bold text-lg">Recently Completed</h3>
                        <a href="{{ route('staff.orders.history') }}"
                            class="text-xs font-black uppercase hover:underline">Full History →</a>
                    </div>
                    @foreach($completed_orders as $id => $order)
                        <div class="bg-white rounded shadow p-4 mb-4 border-l-4 border-green-500 opacity-75">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-bold text-lg">#{{ $order['reference'] }}</span>
                                <span
                                    class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($order['updated_at'])->diffForHumans() }}</span>
                            </div>
                            <div class="text-sm text-gray-600 mb-2">{{ $order['customer_name'] ?? 'Guest' }}</div>
                            <div class="font-bold text-gray-800">Total: RM {{ number_format($order['total_amount'], 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getDatabase, ref, onChildAdded, onChildChanged } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js";

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
        const ordersRef = ref(database, 'orders');

        // Audio for notification
        const notificationSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');

        function requestNotificationPermission() {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    document.getElementById('notif-btn').classList.add('hidden');
                }
            });
        }

        if (Notification.permission === 'granted') {
            document.getElementById('notif-btn').classList.add('hidden');
        }

        function showNotification(order) {
            if (Notification.permission === 'granted') {
                new Notification('New Order Received!', {
                    body: `Order #${order.reference} from ${order.customer_name || 'Guest'}`,
                    icon: '/favicon.ico'
                });
            }
            notificationSound.play().catch(e => console.log('Sound blocked by browser'));
        }

        let initialLoad = true;
        onChildAdded(ordersRef, (snapshot) => {
            const data = snapshot.val();
            if (initialLoad) return; // Skip initial data

            // Only notify for new 'submitted' orders
            if (data.status === 'submitted') {
                showNotification(data);
            }

            // Minimalist delay before reload to see notification/sound
            setTimeout(() => { window.location.reload(); }, 1500);
        });

        onChildChanged(ordersRef, (snapshot) => {
            window.location.reload();
        });

        setTimeout(() => { initialLoad = false; }, 2000);
    </script>
</x-app-layout>