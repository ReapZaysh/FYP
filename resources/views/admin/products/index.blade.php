<x-admin-layout>
    <x-slot name="header">
        <nav class="flex text-sm text-gray-500 gap-2 items-center">
            <a href="{{ route('dashboard') }}" class="hover:text-premium-brown">Dashboard</a>
            <span>/</span>
            <span class="text-premium-brown font-medium">Products</span>
        </nav>
    </x-slot>

    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="font-serif text-3xl text-gray-900">Manage Products</h1>
            <a href="{{ route('admin.products.create') }}"
                class="bg-premium-brown hover:bg-premium-brown/90 text-white font-bold py-2.5 px-6 rounded-2xl shadow-lg shadow-premium-brown/20 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Product
            </a>
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
                            <th class="px-8 py-5">Product</th>
                            <th class="px-8 py-5">Category</th>
                            <th class="px-8 py-5">Price</th>
                            <th class="px-8 py-5">Status</th>
                            <th class="px-8 py-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($products as $id => $product)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        @if(!empty($product['image_path']))
                                            <img src="{{ str_starts_with($product['image_path'], 'http') ? $product['image_path'] : asset('storage/' . $product['image_path']) }}" 
                                                 class="w-12 h-12 rounded-xl object-cover bg-gray-100 shadow-sm">
                                        @else
                                            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 00-2 2z"></path></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-gray-900 font-bold leading-tight">{{ $product['name'] }}</p>
                                            @if(!empty($product['is_featured']))
                                                <span class="text-[10px] font-black uppercase tracking-wider text-amber-500">Featured</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm text-gray-500 font-medium">
                                    {{ isset($categories[$product['category_id']]) ? $categories[$product['category_id']]['name'] : 'Uncategorized' }}
                                </td>
                                <td class="px-8 py-5 text-sm font-bold text-gray-900">
                                    RM {{ number_format($product['price'], 2) }}
                                </td>
                                <td class="px-8 py-5">
                                    @if(!empty($product['is_available']))
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-600 text-[10px] font-black uppercase tracking-wider rounded-full">Available</span>
                                    @else
                                        <span class="px-3 py-1 bg-rose-100 text-rose-600 text-[10px] font-black uppercase tracking-wider rounded-full">Unavailable</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.products.edit', $id) }}"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $id) }}" method="POST"
                                            class="inline-block" onsubmit="return confirm('Delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>