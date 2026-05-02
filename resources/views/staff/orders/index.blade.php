<x-admin-layout>
    <x-slot name="header">
        <nav class="flex text-sm text-gray-500 gap-2 items-center">
            <a href="{{ route('dashboard') }}" class="hover:text-premium-brown">Dashboard</a>
            <span>/</span>
            <span class="text-premium-brown font-medium">Order Management</span>
        </nav>
    </x-slot>

    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="font-serif text-3xl text-gray-900">Order Dashboard</h1>
                <p class="text-gray-500">Monitor and manage real-time customer orders</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="requestNotificationPermission()" id="notif-btn"
                    class="bg-white hover:bg-gray-50 text-gray-600 font-bold py-2.5 px-4 rounded-xl border border-gray-100 text-sm flex items-center gap-2 transition-all group">
                    <div id="notif-icon-container"
                        class="relative text-gray-400 group-hover:text-amber-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <div id="notif-off-slash"
                            class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="w-[120%] h-[2px] bg-red-400 transform -rotate-45 shadow-[0_0_0_2px_#fff]"></div>
                        </div>
                    </div>
                    <span id="notif-text">Alerts Off</span>
                </button>
                <a href="{{ route('staff.orders.history') }}"
                    class="bg-premium-brown hover:bg-premium-brown/90 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-premium-brown/20 transition-all text-sm">
                    View History
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-3"
                role="alert">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-gray-50 p-6 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Total Sales Today</p>
                <h3 class="text-3xl font-black text-gray-900">RM {{ number_format($today_sales, 2) }}</h3>
            </div>
            <div class="flex flex-col items-end gap-2">
                <div class="p-4 bg-emerald-50 rounded-2xl">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <!-- Connection Status Indicator -->
                <div id="connection-status"
                    class="flex items-center gap-2 text-[8px] uppercase tracking-widest font-black text-gray-400">
                    <div class="w-1.5 h-1.5 rounded-full bg-gray-300 animate-pulse" id="status-dot"></div>
                    <span id="status-text">Connecting to Live Server...</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Submitted Orders -->
            <div class="space-y-4">
                <div class="flex items-center justify-between px-2">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        New Orders
                    </h3>
                    <span
                        class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg">{{ $orders->where('status', 'submitted')->count() }}</span>
                </div>
                <div class="space-y-4">
                    @foreach($orders->where('status', 'submitted') as $id => $order)
                        @include('staff.orders.card', ['order' => $order, 'reference' => $id])
                    @endforeach
                    @if($orders->where('status', 'submitted')->isEmpty())
                        <div class="p-12 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-100 text-center">
                            <p class="text-gray-400 text-sm font-medium">All clear!</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- In Progress Orders -->
            <div class="space-y-4">
                <div class="flex items-center justify-between px-2">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        In Progress
                    </h3>
                    <span
                        class="px-2 py-0.5 bg-amber-50 text-amber-600 text-[10px] font-black rounded-lg">{{ $orders->where('status', 'in_progress')->count() }}</span>
                </div>
                <div class="space-y-4">
                    @foreach($orders->where('status', 'in_progress') as $id => $order)
                        @include('staff.orders.card', ['order' => $order, 'reference' => $id])
                    @endforeach
                    @if($orders->where('status', 'in_progress')->isEmpty())
                        <div class="p-12 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-100 text-center">
                            <p class="text-gray-400 text-sm font-medium">Nothing cooking yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recently Completed -->
            <div class="space-y-4">
                <div class="flex items-center justify-between px-2">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Recently Completed
                    </h3>
                    <span
                        class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-lg">{{ $completed_orders->count() }}</span>
                </div>
                <div class="space-y-4">
                    @foreach($completed_orders as $id => $order)
                        <div
                            class="bg-white rounded-3xl shadow-sm border border-gray-50 p-5 group hover:shadow-md transition-all">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <span
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest">#{{ $order['reference'] }}</span>
                                    <h4 class="font-bold text-gray-900 leading-tight">
                                        {{ $order['customer_name'] ?? 'Guest Customer' }}</h4>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-emerald-500 px-2 py-1 bg-emerald-50 rounded-lg">Done</span>
                            </div>
                            <div class="flex justify-between items-end">
                                <span
                                    class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($order['updated_at'])->diffForHumans() }}</span>
                                <span class="font-black text-gray-900">RM
                                    {{ number_format($order['total_amount'], 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Firebase Real-time Synchronization Script --}}
    <script type="module">
        // Import necessary Firebase SDK modules
        console.log("Initializing Firebase for Order Dashboard...");
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getDatabase, ref, onChildAdded, onChildChanged, onValue } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js";

        // Firebase project configuration
        const firebaseConfig = {
            apiKey: "AIzaSyCn5HX9ckfF1mOEO7YtFS9A4Ql1hP58rUw",
            authDomain: "bossku-web.firebaseapp.com",
            projectId: "bossku-web",
            storageBucket: "bossku-web.firebasestorage.app",
            messagingSenderId: "1057285262370",
            appId: "1:1057285262370:web:5337aab034df19510f0e7b",
            databaseURL: "https://bossku-web-default-rtdb.asia-southeast1.firebasedatabase.app"
        };

        // Initialize Firebase and get reference to the Realtime Database
        const app = initializeApp(firebaseConfig);
        const database = getDatabase(app);
        const ordersRef = ref(database, 'orders'); // Reference to the 'orders' node
        const connectedRef = ref(database, '.info/connected'); // Internal Firebase ref to monitor connection state

        // UI elements for the Live Connection Monitor
        const statusDot = document.getElementById('status-dot');
        const statusText = document.getElementById('status-text');

        // Monitor connection status to provide visual feedback (Green = Online, Red = Offline)
        if (statusDot && statusText) {
            onValue(connectedRef, (snap) => {
                if (snap.val() === true) {
                    statusDot.classList.remove('bg-gray-300', 'bg-red-500');
                    statusDot.classList.add('bg-green-500');
                    statusText.innerText = 'Live - Syncing Active';
                    console.log("Firebase Connected");
                } else {
                    statusDot.classList.remove('bg-green-500');
                    statusDot.classList.add('bg-red-500');
                    statusText.innerText = 'Offline - Reconnecting...';
                    console.warn("Firebase Disconnected");
                }
            });
        }

        // Initialize notification sound effect
        const notificationSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');

        /**
         * Updates the Notification Toggle Button UI based on current permission status
         */
        function setNotificationUI(granted) {
            const btn = document.getElementById('notif-btn');
            const slash = document.getElementById('notif-off-slash');
            const text = document.getElementById('notif-text');
            const iconContainer = document.getElementById('notif-icon-container');

            if (!btn) return;

            if (granted) {
                if (slash) slash.classList.add('hidden'); // Hide the "OFF" slash
                if (text) text.innerText = 'Alerts On';
                btn.classList.remove('bg-white', 'text-gray-600', 'border-gray-100');
                btn.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
                iconContainer.classList.remove('text-gray-400');
                iconContainer.classList.add('text-emerald-500');
            } else {
                if (slash) slash.classList.remove('hidden'); // Show the "OFF" slash
                if (text) text.innerText = 'Alerts Off';
                btn.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
                btn.classList.add('bg-white', 'text-gray-600', 'border-gray-100');
                iconContainer.classList.remove('text-amber-500');
                iconContainer.classList.add('text-gray-400');
            }
        }

        /**
         * Requests browser notification permission and handles user choice
         */
        window.requestNotificationPermission = function () {
            if ('Notification' in window) {
                if (Notification.permission === 'denied') {
                    alert("Notifications are blocked by your browser. Please enable them in your browser settings (usually near the URL bar).");
                    return;
                }
                if (Notification.permission === 'granted') {
                    // Already granted, show a test notification
                    new Notification('Notifications are already active!', { icon: '/favicon.ico' });
                    return;
                }

                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        setNotificationUI(true);

                        // Send welcome notification
                        new Notification('Notifications Enabled!', {
                            body: 'You will now receive alerts for new orders.',
                            icon: '/favicon.ico'
                        });
                        const testSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                        testSound.play().catch(e => console.log('Sound blocked'));
                    } else if (permission === 'denied') {
                        alert("Notification permission was denied.");
                        setNotificationUI(false);
                    }
                });
            } else {
                alert("This browser does not support desktop notifications.");
            }
        }

        // Initialize button UI on page load
        if ('Notification' in window && Notification.permission === 'granted') {
            setNotificationUI(true);
        } else {
            setNotificationUI(false);
        }

        /**
         * Displays a desktop notification for a specific order
         */
        function showNotification(order) {
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification('New Order Received!', {
                    body: `Order #${order.reference} from ${order.customer_name || 'Guest'}`,
                    icon: '/favicon.ico'
                });
            }
            notificationSound.play().catch(e => console.log('Sound blocked by browser'));
        }

        let initialData = null;

        // Monitor the 'orders' node for ANY changes
        console.log("Attaching onValue listener to ordersRef...");
        onValue(ordersRef, (snapshot) => {
            try {
                const data = snapshot.val() || {};

                // If it's the first time the page loads, just capture the current state
                if (initialData === null) {
                    initialData = data;
                    console.log("Firebase initial data loaded. Number of orders:", Object.keys(data).length);
                    return;
                }

                // If onValue triggers again, it means a change occurred in Firebase
                console.log("Order changes detected!");

                const oldKeys = Object.keys(initialData);
                const newKeys = Object.keys(data);

                // If the number of items in the list increased, it's a NEW order
                if (newKeys.length > oldKeys.length) {
                    if ('Notification' in window && Notification.permission === 'granted') {
                        new Notification('New Order Received!', {
                            body: `Check the dashboard for new orders.`,
                            icon: '/favicon.ico'
                        });
                    }
                    notificationSound.play().catch(e => console.log('Sound blocked'));

                    // Force refresh after a short delay so the PHP backend can fetch the fresh data
                    setTimeout(() => { window.location.reload(); }, 1500);
                } else {
                    // It was likely a status update or deletion - refresh immediately
                    window.location.reload();
                }
            } catch (err) {
                console.error("Error inside onValue callback:", err);
            }
        }, (errorObject) => {
            // This catches Permission Denied errors or network failures
            console.error("Firebase onValue failed completely. Reason:", errorObject.name, errorObject.message);
        });
        console.log("onValue listener attached.");
    </script>
</x-admin-layout>