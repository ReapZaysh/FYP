<x-customer-layout>
    <div class="max-w-4xl mx-auto px-4 py-8 mb-20">
        <!-- Header & Points Balance -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-3xl p-8 text-white shadow-xl shadow-orange-500/20 mb-8 relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h2 class="text-3xl font-black mb-1">Boss Rewards</h2>
                    <p class="text-white/80 font-medium">Redeem your hard-earned points for delicious treats!</p>
                </div>
                
                <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-6 text-center w-full md:w-auto min-w-[200px]">
                    <span class="block text-white/70 text-xs font-bold uppercase tracking-widest mb-1">Your Boss Points</span>
                    <span class="block text-5xl font-black">{{ number_format($userPoints) }}</span>
                    @guest
                        <a href="{{ route('customer.login') }}" class="inline-block mt-3 text-sm bg-white text-orange-500 hover:bg-orange-50 px-4 py-2 rounded-xl font-bold transition">Log in to earn</a>
                    @endguest
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-2 border-emerald-500 text-emerald-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 shadow-sm">
                <div class="bg-emerald-500 text-white p-1 rounded-full shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-2 border-red-500 text-red-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 shadow-sm">
                <div class="bg-red-500 text-white p-1 rounded-full shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <p class="font-bold">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Rewards List -->
        <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2 transition-colors">
            <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
            Available Rewards
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($rewards as $reward)
                <div class="bg-white dark:bg-gray-900 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden flex flex-col transition hover:shadow-md dark:hover:shadow-amber-900/10 transition-colors duration-300">
                    <div class="h-48 bg-gray-50 dark:bg-gray-800 relative transition-colors">
                        @if(!empty($reward['image_url']))
                            <img src="{{ $reward['image_url'] }}" alt="{{ $reward['name'] }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-600">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-white/90 dark:bg-gray-900/90 backdrop-blur px-4 py-2 rounded-2xl shadow-sm text-amber-600 dark:text-amber-400 font-black flex items-center gap-1 border border-white dark:border-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ number_format($reward['points_required']) }}
                        </div>
                    </div>
                    
                    <div class="p-6 flex-grow flex flex-col">
                        <h3 class="font-black text-gray-900 dark:text-white text-xl mb-2 transition-colors">{{ $reward['name'] }}</h3>
                        <p class="text-gray-500 dark:text-gray-400 font-medium mb-6 flex-grow transition-colors">{{ $reward['description'] }}</p>
                        
                        @auth
                            @if($userPoints >= $reward['points_required'])
                                <form action="{{ route('customer.rewards.redeem', $reward['id']) }}" method="POST" onsubmit="return confirm('Redeem {{ $reward['name'] }} for {{ $reward['points_required'] }} points?');">
                                    @csrf
                                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white py-4 rounded-2xl text-lg font-black shadow-lg shadow-amber-500/20 transition transform hover:scale-[1.02] active:scale-95 text-center block">
                                        Redeem Now
                                    </button>
                                </form>
                            @else
                                <div class="w-full bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500 py-4 rounded-2xl text-lg font-black text-center cursor-not-allowed border border-gray-200 dark:border-gray-700 transition-colors">
                                    Need {{ number_format($reward['points_required'] - $userPoints) }} more points
                                </div>
                            @endif
                        @else
                            <a href="{{ route('customer.login', ['redirect' => route('customer.rewards')]) }}" class="w-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30 py-4 rounded-2xl text-lg font-black transition text-center block border border-blue-100 dark:border-blue-900/30 transition-colors">
                                Log in to Redeem
                            </a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 bg-white dark:bg-gray-900 rounded-[2rem] border border-dashed border-gray-200 dark:border-gray-800 text-center transition-colors">
                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Check back soon!</h3>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">We are preparing some awesome rewards for you.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-customer-layout>
