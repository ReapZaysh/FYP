<x-admin-layout>
    <x-slot name="header">
        <nav class="flex text-sm text-gray-500 gap-2 items-center">
            <a href="{{ route('dashboard') }}" class="hover:text-premium-brown">Dashboard</a>
            <span>/</span>
            <span class="text-premium-brown font-medium">Sales Analytics</span>
        </nav>
    </x-slot>

    <div class="space-y-8">
        <div class="flex justify-between items-end">
            <div>
                <h1 class="font-serif text-3xl text-gray-900">Sales Analytics</h1>
                <p class="text-gray-500">Comprehensive overview of your business performance</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.analytics.export') }}" class="px-4 py-2 bg-white border border-gray-100 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Report
                </a>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-12 h-12 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Today's Sales</div>
                <div class="text-2xl font-black text-gray-900">RM {{ number_format($dailySales[array_key_last($dailySales)] ?? 0, 2) }}</div>
                <div class="mt-2 flex items-center gap-1 text-emerald-500 text-xs font-bold">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span>+12.5%</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-12 h-12 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">This Year</div>
                <div class="text-2xl font-black text-gray-900">RM {{ number_format($yearlySales, 2) }}</div>
                <div class="mt-2 flex items-center gap-1 text-emerald-500 text-xs font-bold">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span>+8.2%</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-12 h-12 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Completed Orders</div>
                <div class="text-2xl font-black text-gray-900">{{ $completedOrders->count() }}</div>
                <div class="mt-2 flex items-center gap-1 text-gray-400 text-xs font-bold">
                    <span>Lifetime</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-12 h-12 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 2a1 1 0 011 1v.68l3.98 1.138A1 1 0 0117.5 5.8a1 1 0 01-.5 1.1l-4 2.25v1.27l3.98 1.138A1 1 0 0117.5 12.8a1 1 0 01-.5 1.1l-4 2.25v.68a1 1 0 11-2 0v-.68l-4-2.25a1 1 0 01-.5-1.1 1 1 0 01.52-.862L11 11.23V9.96l-4-2.25a1 1 0 01-.5-1.1 1 1 0 01.52-.862L11 4.61V3a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Avg Order Value</div>
                <div class="text-2xl font-black text-gray-900">RM {{ $completedOrders->count() > 0 ? number_format($completedOrders->sum('total_amount') / $completedOrders->count(), 2) : '0.00' }}</div>
                <div class="mt-2 flex items-center gap-1 text-rose-500 text-xs font-bold">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                    <span>-2.1%</span>
                </div>
            </div>
        </div>

        <!-- Main Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-50">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-serif text-xl text-gray-900">Daily Sales Trends</h3>
                    <div class="flex bg-gray-100 p-1 rounded-xl">
                        <a href="{{ route('admin.analytics', ['range' => 7]) }}" 
                           class="px-4 py-1.5 {{ $range == 7 ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900' }} text-xs font-bold rounded-lg transition-all">7D</a>
                        <a href="{{ route('admin.analytics', ['range' => 30]) }}" 
                           class="px-4 py-1.5 {{ $range == 30 ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900' }} text-xs font-bold rounded-lg transition-all">30D</a>
                    </div>
                </div>
                <div id="dailyChart" class="h-80"></div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-50">
                <h3 class="font-serif text-xl mb-6 text-gray-900">Monthly Revenue Growth</h3>
                <div id="monthlyChart" class="h-80"></div>
            </div>
        </div>

        <!-- Top Sellers Comparison -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-50">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-serif text-xl text-gray-900">Monthly Top Sellers</h3>
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-wider rounded-full">Current Month</span>
                </div>
                <div class="space-y-4">
                    @forelse($monthlyTopSellers as $id => $item)
                        <div class="flex items-center gap-4 p-4 hover:bg-gray-50 rounded-2xl transition-all border border-transparent hover:border-gray-100">
                            <div class="w-8 text-xs font-bold text-gray-400 italic">{{ $loop->iteration }}</div>
                            <img src="{{ $item['image'] ?? asset('images/placeholder-food.png') }}" class="w-12 h-12 rounded-xl object-cover bg-gray-100">
                            <div class="flex-1">
                                <div class="font-bold text-gray-900 leading-tight">{{ $item['name'] }}</div>
                                <div class="text-xs text-gray-400">{{ $item['quantity'] }} units sold</div>
                            </div>
                            <div class="text-right">
                                <div class="font-black text-gray-900">RM {{ number_format($item['total'], 2) }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-400">No data available yet</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-50">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-serif text-xl text-gray-900">Yearly Top Sellers</h3>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-wider rounded-full">Overall</span>
                </div>
                <div class="space-y-4">
                    @forelse($yearlyTopSellers as $id => $item)
                        <div class="flex items-center gap-4 p-4 hover:bg-gray-50 rounded-2xl transition-all border border-transparent hover:border-gray-100">
                            <div class="w-8 text-xs font-bold text-gray-400 italic">{{ $loop->iteration }}</div>
                            <img src="{{ $item['image'] ?? asset('images/placeholder-food.png') }}" class="w-12 h-12 rounded-xl object-cover bg-gray-100">
                            <div class="flex-1">
                                <div class="font-bold text-gray-900 leading-tight">{{ $item['name'] }}</div>
                                <div class="text-xs text-gray-400">{{ $item['quantity'] }} units sold</div>
                            </div>
                            <div class="text-right">
                                <div class="font-black text-gray-900">RM {{ number_format($item['total'], 2) }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-400">No data available yet</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Weekly Performance -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-50">
            <h3 class="font-serif text-xl mb-6 text-gray-900">Weekly Performance Summary</h3>
            <div id="weeklyChart" class="h-80"></div>
        </div>

        <!-- Recent Completed Orders Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-50 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
                <h3 class="font-serif text-xl text-gray-900">Recent Completed Orders</h3>
                <a href="{{ route('staff.orders.history') }}" class="text-xs font-bold text-premium-brown hover:underline">View All Orders</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <th class="px-8 py-5">Reference</th>
                            <th class="px-8 py-5">Customer</th>
                            <th class="px-8 py-5 text-center">Items</th>
                            <th class="px-8 py-5">Total Amount</th>
                            <th class="px-8 py-5 text-right">Completed At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($completedOrders->sortByDesc('updated_at')->take(5) as $order)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <span class="font-black text-gray-900">#{{ $order['reference'] }}</span>
                                </td>
                                <td class="px-8 py-5 text-sm text-gray-500 font-medium">
                                    {{ $order['customer_name'] ?? 'Guest Customer' }}
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg">{{ count($order['items']) }}</span>
                                </td>
                                <td class="px-8 py-5 text-sm font-black text-gray-900">
                                    RM {{ number_format($order['total_amount'], 2) }}
                                </td>
                                <td class="px-8 py-5 text-right text-xs text-gray-400 font-medium">
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
    <script>
        // Common Chart Config
        const chartColors = {
            primary: '#9c6644',
            secondary: '#e5e7eb',
            accent: '#f59e0b',
            emerald: '#10b981',
            blue: '#3b82f6'
        };

        // Daily Chart
        new ApexCharts(document.querySelector("#dailyChart"), {
            series: [{
                name: 'Daily Sales',
                data: {!! json_encode(array_values($dailySales)) !!}
            }],
            chart: {
                type: 'bar',
                height: '100%',
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false }
            },
            colors: [chartColors.blue],
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    columnWidth: '50%',
                }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: {!! json_encode(array_keys($dailySales)) !!},
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    rotate: -45,
                    rotateAlways: false,
                    style: {
                        colors: '#9ca3af',
                        fontSize: '10px',
                        fontWeight: 600
                    }
                }
            },
            grid: {
                borderColor: '#f3f4f6',
                strokeDashArray: 4,
            },
            yaxis: {
                labels: {
                    formatter: (value) => 'RM ' + Number(value).toFixed(0)
                }
            },
            tooltip: {
                y: {
                    formatter: (value) => 'RM ' + Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                }
            }
        }).render();

        // Monthly Chart
        new ApexCharts(document.querySelector("#monthlyChart"), {
            series: [{
                name: 'Monthly Sales',
                data: {!! json_encode(array_values($monthlySales)) !!}
            }],
            chart: {
                type: 'area',
                height: '100%',
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            colors: [chartColors.emerald],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0,
                    stops: [0, 90, 100]
                }
            },
            stroke: { curve: 'smooth', width: 3 },
            markers: {
                size: 5,
                colors: [chartColors.emerald],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: { size: 7 }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: {!! json_encode(array_keys($monthlySales)) !!},
                axisBorder: { show: false },
                axisTicks: { show: false },
                crosshairs: {
                    show: true,
                    stroke: {
                        color: '#9ca3af',
                        width: 1,
                        dashArray: 3
                    }
                }
            },
            grid: {
                borderColor: '#f3f4f6',
                strokeDashArray: 4,
            },
            yaxis: {
                labels: {
                    formatter: (value) => 'RM ' + Number(value).toFixed(0)
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: (value) => 'RM ' + Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                }
            }
        }).render();

        // Weekly Chart
        new ApexCharts(document.querySelector("#weeklyChart"), {
            series: [{
                name: 'Weekly Sales',
                data: {!! json_encode(array_values($weeklySales)) !!}
            }],
            chart: {
                type: 'line',
                height: '100%',
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false }
            },
            colors: [chartColors.accent],
            stroke: { curve: 'straight', width: 4 },
            markers: { size: 6, strokeWidth: 0, hover: { size: 8 } },
            dataLabels: { enabled: false },
            xaxis: {
                categories: {!! json_encode(array_keys($weeklySales)) !!},
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            grid: {
                borderColor: '#f3f4f6',
                strokeDashArray: 4,
            },
            yaxis: {
                labels: {
                    formatter: (value) => 'RM ' + Number(value).toFixed(0)
                }
            },
            tooltip: {
                y: {
                    formatter: (value) => 'RM ' + Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                }
            }
        }).render();
    </script>
    @endpush
</x-admin-layout>