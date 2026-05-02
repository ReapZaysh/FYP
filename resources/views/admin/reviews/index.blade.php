<x-admin-layout>
    <x-slot name="header">
        <nav class="flex text-sm text-gray-500 gap-2 items-center">
            <a href="{{ route('dashboard') }}" class="hover:text-premium-brown">Dashboard</a>
            <span>/</span>
            <span class="text-premium-brown font-medium">Customer Reviews</span>
        </nav>
    </x-slot>

    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="font-serif text-3xl text-gray-900">Customer Reviews</h1>
                <p class="text-gray-500">Manage and monitor customer feedback</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-3" role="alert">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-gray-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <th class="px-8 py-5">Code</th>
                            <th class="px-8 py-5">Product</th>
                            <th class="px-8 py-5">Rating</th>
                            <th class="px-8 py-5">Customer</th>
                            <th class="px-8 py-5">Comment</th>
                            <th class="px-8 py-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($reviews as $review)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <span class="text-xs font-mono font-black text-gray-400">#{{ $review['code'] }}</span>
                                </td>
                                <td class="px-8 py-5 font-bold text-gray-900">{{ $review['product_name'] }}</td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review['rating'] ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-premium-brown/10 text-premium-brown flex items-center justify-center text-xs font-black">
                                            {{ strtoupper(substr($review['customer_name'] ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-medium {{ ($review['is_anonymous'] ?? false) ? 'text-gray-400 italic' : 'text-gray-900' }}">
                                            {{ ($review['is_anonymous'] ?? false) ? 'Anonymous' : ($review['customer_name'] ?? 'Unknown') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <p class="text-sm text-gray-500 line-clamp-2 max-w-xs">{{ $review['comment'] ?? 'No comment provided.' }}</p>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <form action="{{ route('admin.reviews.destroy', [$review['product_id'], $review['code']]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Delete Review">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                        <p class="text-gray-400 font-medium">No reviews found.</p>
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
