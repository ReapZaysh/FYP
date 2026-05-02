<x-admin-layout>
    <div class="space-y-8">
        <!-- Welcome Section -->
        <div>
            <h1 class="font-serif text-4xl text-gray-900 mb-1">Good morning, {{ Auth::user()->name }}</h1>
            <p class="text-gray-500">Here's what's happening today</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Revenue -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 flex items-center gap-4 relative overflow-hidden group">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-2xl group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zM12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z"></path></svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Today's Revenue</p>
                    <h3 class="text-2xl font-bold text-gray-900">RM {{ number_format($stats['revenue']['value'], 2) }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-xs font-bold {{ $stats['revenue']['trend'] === 'up' ? 'text-emerald-500' : 'text-rose-500' }}">
                            {{ $stats['revenue']['trend'] === 'up' ? '+' : '' }}{{ number_format($stats['revenue']['growth'], 1) }}%
                        </span>
                        <span class="text-[10px] text-gray-400 font-medium">vs yesterday</span>
                    </div>
                </div>
                <!-- Mini Sparkline Placeholder -->
                <div class="absolute bottom-0 right-0 w-24 h-12 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 40" preserveAspectRatio="none">
                        <path d="M0 35 Q 25 35 40 20 T 70 25 T 100 15" fill="none" stroke="currentColor" stroke-width="4"></path>
                    </svg>
                </div>
            </div>

            <!-- Orders -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 flex items-center gap-4 relative overflow-hidden group">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Orders</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['orders']['value'] }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-xs font-bold {{ $stats['orders']['trend'] === 'up' ? 'text-emerald-500' : 'text-rose-500' }}">
                            {{ $stats['orders']['trend'] === 'up' ? '+' : '' }}{{ number_format($stats['orders']['growth'], 1) }}%
                        </span>
                        <span class="text-[10px] text-gray-400 font-medium">vs yesterday</span>
                    </div>
                </div>
            </div>

            <!-- Avg Order -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 flex items-center gap-4 relative overflow-hidden group">
                <div class="p-3 bg-orange-50 text-orange-600 rounded-2xl group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Avg Order</p>
                    <h3 class="text-2xl font-bold text-gray-900">RM {{ number_format($stats['avg_order']['value'], 2) }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-xs font-bold text-emerald-500">+2.4%</span>
                        <span class="text-[10px] text-gray-400 font-medium">vs yesterday</span>
                    </div>
                </div>
            </div>

            <!-- Active Products -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 flex items-center gap-4 relative overflow-hidden group">
                <div class="p-3 bg-rose-50 text-rose-600 rounded-2xl group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Active Products</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['active_products']['value'] }}</h3>
                    @if($stats['active_products']['low_stock'] > 0)
                        <div class="mt-1">
                            <span class="px-2 py-0.5 bg-orange-100 text-orange-600 text-[10px] font-bold rounded-full">
                                {{ $stats['active_products']['low_stock'] }} low stock
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sales Overview -->
            <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow-sm border border-gray-50">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-bold text-gray-900">Sales Overview</h3>
                    <div class="flex bg-gray-100 p-1 rounded-xl">
                        <a href="{{ route('admin.dashboard', ['range' => 7]) }}" 
                           class="px-4 py-1.5 {{ $range == 7 ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900' }} text-xs font-bold rounded-lg transition-all">7D</a>
                        <a href="{{ route('admin.dashboard', ['range' => 30]) }}" 
                           class="px-4 py-1.5 {{ $range == 30 ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900' }} text-xs font-bold rounded-lg transition-all">30D</a>
                        <a href="{{ route('admin.dashboard', ['range' => 90]) }}" 
                           class="px-4 py-1.5 {{ $range == 90 ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900' }} text-xs font-bold rounded-lg transition-all">90D</a>
                    </div>
                </div>
                <div id="salesChart" class="h-80 w-full"></div>
            </div>

            <!-- Top Products -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-50 flex flex-col">
                <h3 class="text-xl font-bold text-gray-900 mb-8">Top Products</h3>
                <div class="flex-1 space-y-6">
                    @forelse($topProducts as $id => $product)
                        <div class="flex items-center gap-4">
                            <div class="w-8 text-sm font-bold text-gray-400 italic">{{ $loop->iteration }}</div>
                            <img src="{{ $product['image'] ?? asset('images/placeholder-food.png') }}" class="w-12 h-12 rounded-xl object-cover bg-gray-100">
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-900 leading-tight">{{ $product['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $product['quantity'] }} sold</p>
                            </div>
                            <div class="text-right">
                                <div class="w-32 bg-gray-100 h-1.5 rounded-full overflow-hidden mb-1">
                                    <div class="bg-premium-brown h-full rounded-full" style="width: {{ ($product['total'] / $topProducts->first()['total']) * 100 }}%"></div>
                                </div>
                                <p class="text-xs font-bold text-gray-900">RM {{ number_format($product['total'], 2) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-12 italic">No sales data yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Bottom Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Orders -->
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-50 overflow-hidden flex flex-col">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Recent Orders</h3>
                    <a href="{{ route('staff.orders.index') }}" class="text-premium-brown text-sm font-bold hover:underline">View all orders →</a>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                <th class="px-8 py-4">Order#</th>
                                <th class="px-8 py-4">Customer</th>
                                <th class="px-8 py-4">Items</th>
                                <th class="px-8 py-4">Total</th>
                                <th class="px-8 py-4">Status</th>
                                <th class="px-8 py-4">Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($recentOrders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-8 py-4 text-sm font-bold text-gray-900">#{{ $order['reference'] }}</td>
                                    <td class="px-8 py-4 text-sm text-gray-600 font-medium">{{ $order['customer_name'] ?? 'Guest' }}</td>
                                    <td class="px-8 py-4 text-sm text-gray-500 font-bold">{{ count($order['items'] ?? []) }}</td>
                                    <td class="px-8 py-4 text-sm text-gray-900 font-bold">RM {{ number_format($order['total_amount'], 2) }}</td>
                                    <td class="px-8 py-4">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                            {{ ($order['status'] ?? '') === 'completed' ? 'bg-emerald-100 text-emerald-600' : 
                                               (($order['status'] ?? '') === 'submitted' ? 'bg-blue-100 text-blue-600' : 'bg-orange-100 text-orange-600') }}">
                                            {{ $order['status'] ?? 'Pending' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-4 text-xs text-gray-400">{{ \Carbon\Carbon::parse($order['updated_at'])->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Category Performance -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-50">
                <h3 class="text-xl font-bold text-gray-900 mb-8">Category Performance</h3>
                <div id="categoryChart" class="h-64"></div>
                <div class="mt-8 space-y-4">
                    @foreach($categoryData as $name => $revenue)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">{{ $name }}</span>
                            <span class="text-sm font-bold text-gray-900">RM {{ number_format($revenue, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="flex flex-wrap gap-4 pt-4 pb-8">
            <a href="{{ route('admin.products.create') }}" class="flex items-center gap-2 px-6 py-3 bg-premium-brown text-white font-bold rounded-2xl shadow-lg shadow-premium-brown/30 hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Product
            </a>
            <a href="{{ route('admin.categories.create') }}" class="flex items-center gap-2 px-6 py-3 bg-white text-gray-900 font-bold rounded-2xl shadow-sm border border-gray-200 hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                New Category
            </a>
            <a href="{{ route('admin.analytics') }}" class="flex items-center gap-2 px-6 py-3 bg-white text-gray-900 font-bold rounded-2xl shadow-sm border border-gray-200 hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m-8-2h11a2 2 0 012 2v11a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"></path></svg>
                Detailed Analytics
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-2 px-6 py-3 bg-white text-gray-900 font-bold rounded-2xl shadow-sm border border-gray-200 hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.783.57-1.837-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                Reviews
            </a>
        </div>
    </div>

    @push('scripts')
    <script>
        // Sales Overview Chart
        var salesOptions = {
            series: [{
                name: 'Revenue',
                data: {!! json_encode(array_values($dailySales)) !!}
            }],
            chart: {
                type: 'area',
                height: 320,
                toolbar: { show: false },
                zoom: { enabled: false },
                fontFamily: 'Inter, sans-serif'
            },
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: ['#9c6644']
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100, 100, 100],
                    colorStops: [
                        { offset: 0, color: "#9c6644", opacity: 0.4 },
                        { offset: 100, color: "#9c6644", opacity: 0 }
                    ]
                }
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 4,
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: false } }
            },
            xaxis: {
                categories: {!! json_encode(array_keys($dailySales)) !!},
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#9ca3af', fontWeight: 600 } }
            },
            yaxis: {
                labels: {
                    style: { colors: '#9ca3af', fontWeight: 600 },
                    formatter: (value) => 'RM ' + value.toFixed(0)
                }
            },
            tooltip: {
                y: { formatter: (value) => 'RM ' + Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") }
            }
        };
        new ApexCharts(document.querySelector("#salesChart"), salesOptions).render();

        // Category Performance Chart
        var categoryOptions = {
            series: [{
                name: 'Revenue',
                data: {!! json_encode(array_values($categoryData)) !!}
            }],
            chart: {
                type: 'bar',
                height: 250,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '30%',
                    borderRadius: 4,
                }
            },
            colors: ['#9c6644'],
            dataLabels: { enabled: false },
            grid: { show: false },
            xaxis: {
                categories: {!! json_encode(array_keys($categoryData)) !!},
                labels: { show: false },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: { style: { colors: '#374151', fontWeight: 600 } }
            },
            tooltip: {
                x: { show: true },
                y: { formatter: (value) => 'RM ' + Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") }
            }
        };
        new ApexCharts(document.querySelector("#categoryChart"), categoryOptions).render();
    </script>
    @endpush
</x-admin-layout>