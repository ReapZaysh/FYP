<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sales Analytics') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-blue-500 hover:underline">Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                    <div class="text-sm text-gray-500 uppercase font-bold">Today's Sales</div>
                    <div class="text-2xl font-bold">RM {{ number_format($dailySales[array_key_last($dailySales)], 2) }}
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                    <div class="text-sm text-gray-500 uppercase font-bold">This Year</div>
                    <div class="text-2xl font-bold">RM {{ number_format($yearlySales, 2) }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
                    <div class="text-sm text-gray-500 uppercase font-bold">Completed Orders</div>
                    <div class="text-2xl font-bold">{{ $completedOrders->count() }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
                    <div class="text-sm text-gray-500 uppercase font-bold">Avg Order Value</div>
                    <div class="text-2xl font-bold">RM
                        {{ $completedOrders->count() > 0 ? number_format($completedOrders->sum('total_amount') / $completedOrders->count(), 2) : '0.00' }}
                    </div>
                </div>
            </div>

            <!-- Top Sellers Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4 text-blue-600">🏆 Top Sellers (This Month)</h3>
                    <div class="space-y-4">
                        @forelse($monthlyTopSellers as $id => $item)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-bold text-gray-900">{{ $item['name'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $item['quantity'] }} units sold</div>
                                </div>
                                <div class="font-bold text-blue-600">RM {{ number_format($item['total'], 2) }}</div>
                            </div>
                        @empty
                            <p class="text-gray-400 italic">No data yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4 text-green-600">👑 Yearly Choice (Most Sold)</h3>
                    <div class="space-y-4">
                        @forelse($yearlyTopSellers as $id => $item)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-bold text-gray-900">{{ $item['name'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $item['quantity'] }} units sold</div>
                                </div>
                                <div class="font-bold text-green-600">RM {{ number_format($item['total'], 2) }}</div>
                            </div>
                        @empty
                            <p class="text-gray-400 italic">No data yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Charts Container -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Daily Sales Chart -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">Daily Sales (Last 7 Days)</h3>
                    <canvas id="dailyChart"></canvas>
                </div>

                <!-- Monthly Sales Chart -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">Monthly Sales (Last 6 Months)</h3>
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <!-- Weekly Sales Summary -->
            <div class="bg-white p-6 rounded-lg shadow mb-8">
                <h3 class="text-lg font-bold mb-4">Weekly Performance</h3>
                <canvas id="weeklyChart" style="max-height: 300px;"></canvas>
            </div>

            <!-- Detailed Data Tables -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-4 bg-gray-50 border-b">
                    <h3 class="font-bold">Recent Completed Orders</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Completed At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($completedOrders->sortByDesc('updated_at')->take(10) as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-sm">#{{ $order['reference'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $order['customer_name'] ?? 'Guest' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ count($order['items']) }} item(s)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">RM
                                    {{ number_format($order['total_amount'], 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    {{ \Carbon\Carbon::parse($order['updated_at'])->format('d M, H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Daily Chart
            const dailyCtx = document.getElementById('dailyChart').getContext('2d');
            new Chart(dailyCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($dailySales)) !!},
                    datasets: [{
                        label: 'Sales (RM)',
                        data: {!! json_encode(array_values($dailySales)) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            // Monthly Chart
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_keys($monthlySales)) !!},
                    datasets: [{
                        label: 'Sales (RM)',
                        data: {!! json_encode(array_values($monthlySales)) !!},
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            // Weekly Chart
            const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
            new Chart(weeklyCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($weeklySales)) !!},
                    datasets: [{
                        label: 'Weekly Sales (RM)',
                        data: {!! json_encode(array_values($weeklySales)) !!},
                        backgroundColor: 'rgba(245, 158, 11, 0.5)',
                        borderColor: 'rgb(245, 158, 11)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>