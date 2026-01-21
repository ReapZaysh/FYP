<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order History') }}
            </h2>
            <a href="{{ route('staff.orders.index') }}" class="text-blue-500 hover:underline">Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters & Search -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <form action="{{ route('staff.orders.history') }}" method="GET" class="flex flex-col md:flex-row gap-6">
                    <!-- Search -->
                    <div class="flex-grow">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Search Order #</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="e.g. 5A9C">
                        </div>
                    </div>

                    <!-- Filter Presets -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Time Period</label>
                        <select name="filter"
                            class="block w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            onchange="this.form.submit()">
                            <option value="today" {{ $filter === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ $filter === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="month" {{ $filter === 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="custom" {{ $filter === 'custom' ? 'selected' : '' }}>Custom Date</option>
                        </select>
                    </div>

                    <!-- Custom Date Picker (Visible if custom selected) -->
                    @if($filter === 'custom')
                        <div x-data="{ date: '{{ request('date') }}' }">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Specific Date</label>
                            <input type="date" name="date" value="{{ request('date') }}"
                                class="block w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                onchange="this.form.submit()">
                        </div>
                    @endif

                    <div class="flex items-end">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-lg shadow-blue-200">
                            Apply
                        </button>
                        <a href="{{ route('staff.orders.history') }}"
                            class="ml-4 text-gray-500 hover:text-gray-700 py-2">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Order #
                            </th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Customer
                            </th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Items</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Total</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($history as $id => $order)
                            <tr class="hover:bg-gray-50/50 transition leading-tight">
                                <td class="px-6 py-4 font-black text-gray-900">#{{ $order['reference'] }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $order['customer_name'] ?? 'Guest' }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500">
                                        @foreach($order['items'] as $item)
                                            <span>{{ $item['quantity'] }}x
                                                {{ $item['name'] }}</span>{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">RM
                                    {{ number_format($order['total_amount'], 2) }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-black uppercase {{ $order['status'] === 'completed' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                        {{ $order['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-400 text-sm">
                                    {{ \Carbon\Carbon::parse($order['updated_at'])->format('d M, h:i A') }}
                                    <div class="text-[10px]">
                                        {{ \Carbon\Carbon::parse($order['updated_at'])->diffForHumans() }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                                    No orders found for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>