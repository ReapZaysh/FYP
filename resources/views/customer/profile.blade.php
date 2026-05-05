<x-customer-layout>
    <div class="max-w-4xl mx-auto px-4 py-8 mb-20">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors">My Profile</h2>
                <p class="text-gray-500 dark:text-gray-400 font-medium transition-colors">Manage your account and view rewards</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-xl transition text-sm flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Log Out
                </button>
            </form>
        </div>

        <!-- User Info & Points Card -->
        <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl p-8 text-white shadow-xl mb-8 relative overflow-hidden">
            <!-- Decorative circle -->
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            
            <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center text-gray-900 dark:text-white text-3xl font-black shrink-0 border-4 border-gray-700 transition-colors">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                
                <div class="flex-grow text-center md:text-left">
                    <h3 class="text-2xl font-black mb-1">{{ $user->name }}</h3>
                    <p class="text-gray-400 font-medium mb-4">{{ $user->email }}</p>
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-white/10 rounded-full text-xs font-bold text-gray-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        Customer
                    </span>
                </div>

                <div class="text-center md:text-right bg-white/10 p-6 rounded-2xl backdrop-blur-sm border border-white/5 w-full md:w-auto">
                    <span class="block text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Loyalty Points</span>
                    <span class="block text-5xl font-black text-amber-400">{{ $loyaltyPoints }}</span>
                    <a href="{{ route('customer.rewards') }}" class="inline-block mt-3 text-sm text-white font-bold hover:text-amber-400 transition">View Rewards &rarr;</a>
                </div>
            </div>
        </div>

        <!-- My Vouchers -->
        @if($vouchers->count() > 0)
            <div class="mb-10">
                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-4 flex items-center gap-2 transition-colors">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    My Active Vouchers
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($vouchers as $voucher)
                        <div class="bg-emerald-50 dark:bg-emerald-900/10 border-2 border-emerald-500/20 dark:border-emerald-900/30 rounded-2xl p-4 flex items-center gap-4 relative overflow-hidden shadow-sm transition-colors">
                            <div class="absolute -right-4 -top-4 w-16 h-16 bg-emerald-500/10 rounded-full blur-xl"></div>
                            <div class="bg-emerald-500 text-white w-12 h-12 rounded-xl flex items-center justify-center shrink-0 shadow-inner">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-black text-gray-900 dark:text-white transition-colors">{{ $voucher['reward_name'] }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold transition-colors">Redeemed {{ \Carbon\Carbon::parse($voucher['created_at'])->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recent Orders -->
        <div>
            <h3 class="text-xl font-black text-gray-900 dark:text-white mb-4 flex items-center gap-2 transition-colors">
                <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Recent Orders
            </h3>

            @if($recentOrders->count() > 0)
                <div class="space-y-4">
                    @foreach($recentOrders as $order)
                        <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition hover:shadow-md transition-colors">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="font-mono font-black text-gray-900 dark:text-white transition-colors">#{{ $order['reference'] }}</span>
                                    @if(($order['payment_status'] ?? '') === 'paid')
                                        <span class="px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] font-black uppercase tracking-wider rounded-full transition-colors">Paid</span>
                                    @else
                                        <span class="px-2.5 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-[10px] font-black uppercase tracking-wider rounded-full transition-colors">{{ $order['status'] }}</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium transition-colors">
                                    {{ count($order['items'] ?? []) }} items • RM {{ number_format($order['total_amount'] ?? 0, 2) }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ \Carbon\Carbon::parse($order['created_at'])->format('d M Y, h:i A') }}
                                </p>
                            </div>
                            <a href="{{ route('customer.track', $order['reference']) }}" class="px-4 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30 font-bold rounded-xl transition text-sm text-center border border-blue-100 dark:border-blue-900/30 transition-colors">
                                View Status
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-3xl border border-dashed border-gray-200 dark:border-gray-800 transition-colors">
                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-gray-500 dark:text-gray-400 font-medium transition-colors">You haven't placed any orders yet.</p>
                </div>
            @endif
        </div>
    </div>
</x-customer-layout>
