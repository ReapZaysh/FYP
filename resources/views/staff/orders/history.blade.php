<x-admin-layout>
    <x-slot name="header">
        <nav class="flex text-sm text-gray-500 gap-2 items-center">
            <a href="{{ route('dashboard') }}" class="hover:text-premium-brown">Dashboard</a>
            <span>/</span>
            <a href="{{ route('staff.orders.index') }}" class="hover:text-premium-brown">Orders</a>
            <span>/</span>
            <span class="text-premium-brown font-medium">Order History</span>
        </nav>
    </x-slot>

    <div class="space-y-8">
        <div class="flex justify-between items-end">
            <div>
                <h1 class="font-serif text-3xl text-gray-900">Order History</h1>
                <p class="text-gray-500">Review past orders and performance reports</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('staff.orders.index') }}" class="px-4 py-2 bg-white border border-gray-100 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-50 p-8">
            <form action="{{ route('staff.orders.history') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Search -->
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Search Order</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center">
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-premium-brown/20 focus:border-premium-brown transition-all text-sm"
                            placeholder="Order Reference...">
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Order Status</label>
                    <select name="status"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-premium-brown/20 focus:border-premium-brown transition-all text-sm appearance-none"
                        onchange="this.form.submit()">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                </div>

                <!-- Time Period -->
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Time Period</label>
                    <select name="filter"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-premium-brown/20 focus:border-premium-brown transition-all text-sm appearance-none"
                        onchange="this.form.submit()">
                        <option value="today" {{ $filter === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="yesterday" {{ $filter === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                        <option value="month" {{ $filter === 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="custom" {{ $filter === 'custom' ? 'selected' : '' }}>Custom Date</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-premium-brown hover:bg-premium-brown/90 text-white font-bold py-2.5 rounded-xl transition-all text-sm">
                        Apply Filters
                    </button>
                    <a href="{{ route('staff.orders.history') }}" class="p-2.5 bg-gray-50 text-gray-400 hover:text-gray-600 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </a>
                </div>
            </form>

            <!-- Custom Date Picker -->
            @if($filter === 'custom')
                <div class="mt-4 pt-4 border-t border-gray-50">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Specific Date</label>
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="w-full max-w-xs px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-premium-brown/20 focus:border-premium-brown transition-all text-sm"
                        onchange="this.form.submit()">
                </div>
            @endif

            <hr class="my-8 border-gray-50">

            <!-- Report Generation -->
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-premium-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 leading-tight">Export PDF Report</h4>
                        <p class="text-xs text-gray-400">Generate detailed order reports for your records</p>
                    </div>
                </div>
                <form action="{{ route('staff.orders.report') }}" method="GET" class="flex flex-wrap items-center gap-4">
                    <select name="status" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-premium-brown/20 appearance-none">
                        <option value="all">All Archived</option>
                        <option value="completed" selected>Completed Only</option>
                        <option value="canceled">Canceled Only</option>
                    </select>
                    <select name="type" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-premium-brown/20 appearance-none">
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                    <button type="submit" class="bg-white hover:bg-gray-50 text-premium-brown font-bold py-2 px-6 rounded-xl text-xs transition border border-gray-200 shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Generate PDF
                    </button>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <th class="px-8 py-5">Order Reference</th>
                            <th class="px-8 py-5">Customer Details</th>
                            <th class="px-8 py-5">Items Summary</th>
                            <th class="px-8 py-5">Total Amount</th>
                            <th class="px-8 py-5">Status</th>
                            <th class="px-8 py-5 text-right">Date & Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($history as $id => $order)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <span class="text-sm font-black text-gray-900">#{{ $order['reference'] }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 leading-tight">{{ $order['customer_name'] ?? 'Guest Customer' }}</span>
                                        <span class="text-[10px] text-gray-400 uppercase tracking-wider font-black mt-1">Table {{ $order['table_number'] ?? 'Counter' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @foreach($order['items'] as $item)
                                            <span class="px-2 py-0.5 bg-gray-100 text-[10px] font-bold text-gray-600 rounded-md">
                                                {{ $item['quantity'] }}x {{ $item['name'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-8 py-5 font-black text-gray-900 text-sm">
                                    RM {{ number_format($order['total_amount'], 2) }}
                                </td>
                                <td class="px-8 py-5">
                                    @php
                                        $isPaid = ($order['payment_status'] ?? '') === 'paid';
                                        $badgeClass = $isPaid ? 'bg-emerald-50 text-emerald-600' : ($order['status'] === 'canceled' ? 'bg-rose-50 text-rose-600' : 'bg-gray-50 text-gray-400');
                                        $badgeLabel = $isPaid ? 'Paid' : ($order['status'] === 'canceled' ? 'Canceled' : ucwords(str_replace('_', ' ', $order['status'])));
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $badgeClass }}">
                                        {{ $badgeLabel }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex flex-col items-end">
                                        <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($order['updated_at'])->format('d M Y') }}</span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">{{ \Carbon\Carbon::parse($order['updated_at'])->format('h:i A') }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        <p class="text-gray-400 font-medium">No orders found for this period.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>