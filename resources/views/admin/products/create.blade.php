<x-admin-layout>
    <x-slot name="header">
        <nav class="flex text-sm text-gray-500 gap-2 items-center">
            <a href="{{ route('dashboard') }}" class="hover:text-premium-brown">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.products.index') }}" class="hover:text-premium-brown">Products</a>
            <span>/</span>
            <span class="text-premium-brown font-medium">Add Product</span>
        </nav>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <div>
            <h1 class="font-serif text-3xl text-gray-900">Add New Product</h1>
            <p class="text-gray-500">Create a new item for your menu</p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-50 p-8">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="name">Product Name</label>
                            <input
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-premium-brown/20 focus:border-premium-brown transition-all"
                                id="name" name="name" type="text" placeholder="e.g. Signature Burger" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="category_id">Category</label>
                            <select
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-premium-brown/20 focus:border-premium-brown transition-all appearance-none"
                                id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $id => $category)
                                    <option value="{{ $id }}">{{ $category['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="price">Price (RM)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-bold">RM</span>
                                <input
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-premium-brown/20 focus:border-premium-brown transition-all"
                                    id="price" name="price" type="number" step="0.01" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="description">Description</label>
                            <textarea
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-premium-brown/20 focus:border-premium-brown transition-all"
                                id="description" name="description" placeholder="Describe this delicious dish..." rows="4"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2" for="image">Product Image</label>
                            <div class="flex gap-4 items-center w-full">
                                <img id="imagePreview" src="" class="hidden w-32 h-32 rounded-2xl object-cover bg-gray-100 border border-gray-100">
                                <label class="flex-1 flex flex-col items-center justify-center h-32 border-2 border-gray-100 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <p class="text-xs text-gray-500">Click to upload product image</p>
                                    </div>
                                    <input id="image" name="image" type="file" class="hidden" accept="image/*" onchange="if(this.files[0]) { let preview = document.getElementById('imagePreview'); preview.src = window.URL.createObjectURL(this.files[0]); preview.classList.remove('hidden'); }" />
                                </label>
                            </div>
                        </div>
                        <div class="flex gap-6 pt-2">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="is_available" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-premium-brown"></div>
                                </div>
                                <span class="text-sm font-bold text-gray-600 group-hover:text-gray-900 transition-colors">Available</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="is_featured" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-400"></div>
                                </div>
                                <span class="text-sm font-bold text-gray-600 group-hover:text-gray-900 transition-colors">Featured</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-8 border-t border-gray-50">
                    <a href="{{ route('admin.products.index') }}"
                        class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">Cancel</a>
                    <button
                        class="bg-premium-brown hover:bg-premium-brown/90 text-white font-bold py-3 px-10 rounded-2xl shadow-lg shadow-premium-brown/20 transition-all flex items-center gap-2"
                        type="submit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Create Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>