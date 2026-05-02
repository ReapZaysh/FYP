<x-admin-layout>
    <x-slot name="header">
        <nav class="flex text-sm text-gray-500 gap-2 items-center">
            <a href="{{ route('dashboard') }}" class="hover:text-premium-brown">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.categories.index') }}" class="hover:text-premium-brown">Categories</a>
            <span>/</span>
            <span class="text-premium-brown font-medium">Edit</span>
        </nav>
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <h1 class="font-serif text-3xl text-gray-900">Edit Category</h1>
            <p class="text-gray-500">Updating {{ $category['name'] }}</p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-50 p-8">
            <form method="POST" action="{{ route('admin.categories.update', $id) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2" for="name">Category Name</label>
                    <input
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-premium-brown/20 focus:border-premium-brown transition-all"
                        id="name" name="name" type="text" value="{{ $category['name'] }}" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2" for="sort_order">Display Order</label>
                    <input
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-premium-brown/20 focus:border-premium-brown transition-all"
                        id="sort_order" name="sort_order" type="number" value="{{ $category['sort_order'] }}">
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-gray-50">
                    <button
                        class="bg-premium-brown hover:bg-premium-brown/90 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-premium-brown/20 transition-all flex items-center gap-2"
                        type="submit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Update Category
                    </button>
                    <a href="{{ route('admin.categories.index') }}"
                        class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>