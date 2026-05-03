<x-admin-layout>
    <x-slot name="header">
        <nav class="flex text-sm text-gray-500 gap-2 items-center">
            <a href="{{ route('dashboard') }}" class="hover:text-premium-brown">Dashboard</a>
            <span>/</span>
            <span class="text-premium-brown font-medium">Customer Reviews</span>
        </nav>
    </x-slot>

    <div class="space-y-10">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="font-serif text-3xl text-gray-900">Customer Reviews</h1>
                <p class="text-gray-500">Separated by products for better visibility</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-3" role="alert">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @forelse($groupedReviews as $productId => $reviews)
            @php 
                $firstReview = $reviews->first();
                $avgRating = $reviews->avg('rating');
            @endphp
            <div x-data="{ open: false }" class="space-y-4">
                {{-- Product Header Card (Toggle) --}}
                <div @click="open = !open" 
                     class="flex items-center justify-between bg-white p-6 rounded-3xl shadow-sm border border-gray-50 cursor-pointer hover:shadow-md transition-all group">
                    <div class="flex items-center gap-4">
                        @if($firstReview['product_image'])
                            <img src="{{ $firstReview['product_image'] }}" class="w-16 h-16 rounded-2xl object-cover border border-gray-100 group-hover:scale-105 transition-transform">
                        @else
                            <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-premium-brown transition-colors">{{ $firstReview['product_name'] }}</h3>
                            <div class="flex items-center gap-2">
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3 {{ $i <= round($avgRating) ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs font-bold text-gray-500">{{ number_format($avgRating, 1) }} Average</span>
                                <span class="text-gray-300">•</span>
                                <span class="text-xs font-medium text-gray-400">{{ $reviews->count() }} total reviews</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 group-hover:text-premium-brown transition-colors" x-text="open ? 'Hide Reviews' : 'Show Reviews'">Show Reviews</span>
                        <div class="p-2 bg-gray-50 rounded-xl group-hover:bg-premium-brown group-hover:text-white transition-all transform" :class="open && 'rotate-180'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Reviews Table for this Product --}}
                <div x-show="open" x-transition:enter.duration.300ms class="bg-white rounded-3xl shadow-sm border border-gray-50 overflow-hidden" x-cloak>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    <th class="px-8 py-4">Code</th>
                                    <th class="px-8 py-4">Rating</th>
                                    <th class="px-8 py-4">Customer</th>
                                    <th class="px-8 py-4">Comment</th>
                                    <th class="px-8 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($reviews as $review)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-8 py-4">
                                            <span class="text-xs font-mono font-black text-gray-400">#{{ $review['code'] }}</span>
                                        </td>
                                        <td class="px-8 py-4">
                                            <div class="flex items-center gap-0.5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3.5 h-3.5 {{ $i <= $review['rating'] ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </td>
                                        <td class="px-8 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-premium-brown/10 text-premium-brown flex items-center justify-center text-[10px] font-black">
                                                    {{ strtoupper(substr($review['customer_name'] ?? 'U', 0, 1)) }}
                                                </div>
                                                <span class="text-sm font-medium {{ ($review['is_anonymous'] ?? false) ? 'text-gray-400 italic' : 'text-gray-900' }}">
                                                    {{ ($review['is_anonymous'] ?? false) ? 'Anonymous' : ($review['customer_name'] ?? 'Unknown') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-4">
                                            <p class="text-sm text-gray-500 max-w-sm">{{ $review['comment'] ?? 'No comment provided.' }}</p>
                                        </td>
                                        <td class="px-8 py-4 text-right">
                                            <form action="{{ route('admin.reviews.destroy', [$review['product_id'], $review['code']]) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-gray-300 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-16 bg-white rounded-3xl border-2 border-dashed border-gray-100 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </div>
                <p class="text-gray-900 font-bold text-xl mb-1">No reviews yet</p>
                <p class="text-gray-400 text-sm">Customer feedback will appear here grouped by product.</p>
            </div>
        @endforelse
    </div>
</x-admin-layout>
