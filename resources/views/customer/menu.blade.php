<x-customer-layout>
    <div x-data="{ 
        search: '',
        isModalOpen: false,
        selectedProduct: { id: '', name: '', price: 0, image: '', description: '' },
        qty: 1,

        openModal(id, name, price, image, description) {
            this.selectedProduct = { id, name, price, image, description };
            this.qty = 1;
            this.isModalOpen = true;
            document.body.classList.add('overflow-hidden');
        },
        closeModal() {
            this.isModalOpen = false;
            document.body.classList.remove('overflow-hidden');
        },
        filterProducts(name) {
            return name.toLowerCase().includes(this.search.toLowerCase());
        },
        hasVisibleProducts(products) {
            return Object.values(products).some(p => this.filterProducts(p.name));
        }
    }" @keydown.escape="closeModal()">
        
        <!-- Search Section -->
        <div class="bg-white shadow-sm sticky top-0 z-50 py-4 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input x-model="search" type="text" 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-full leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out" 
                        placeholder="Search for wings, drinks, or anything...">
                </div>
            </div>
        </div>

        <!-- Featured Section (Hidden when searching) -->
        <template x-if="search === ''">
            @if(count($featuredProducts) > 0)
                <div class="py-6 px-4 max-w-7xl mx-auto">
                    <h3 class="text-xl font-bold mb-4 px-2">Featured Items</h3>
                    <div class="flex overflow-x-auto space-x-4 pb-4 scrollbar-hide px-2">
                        @foreach($featuredProducts as $id => $product)
                            <div @click="openModal('{{ $id }}', '{{ addslashes($product['name']) }}', {{ $product['price'] }}, '{{ $product['image_path'] ?? '' }}', '{{ addslashes($product['description'] ?? '') }}')" 
                                 class="flex-shrink-0 w-64 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer transition transform hover:scale-[1.02]">
                                @if(!empty($product['image_path']))
                                    <img class="h-40 w-full object-cover" src="{{ asset('storage/' . $product['image_path']) }}" alt="{{ $product['name'] }}">
                                @else
                                    <div class="h-40 w-full bg-gray-100 flex items-center justify-center text-gray-400">No Image</div>
                                @endif
                                <div class="p-4">
                                    <h4 class="font-bold text-lg mb-1">{{ $product['name'] }}</h4>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="font-bold text-lg text-blue-600">RM {{ number_format($product['price'], 2) }}</span>
                                        <button class="bg-blue-500 text-white p-2 rounded-full shadow-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </template>

        <!-- Dynamic Trending Sections -->
        <div class="py-6 px-4 max-w-7xl mx-auto space-y-10">
            <!-- Monthly Trending -->
            @if(count($monthlyTrending) > 0)
                <div x-show="search === ''">
                    <h3 class="text-2xl font-black mb-4 px-2 flex items-center justify-between">
                        <span>🔥 Trending This Month</span>
                        <span class="text-xs font-bold bg-blue-100 text-blue-600 px-3 py-1 rounded-full uppercase">Top 3</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-2">
                        @foreach($monthlyTrending as $id => $product)
                            <div @click="openModal('{{ $id }}', '{{ addslashes($product['name']) }}', {{ $product['price'] }}, '{{ $product['image_path'] ?? '' }}', '{{ addslashes($product['description'] ?? '') }}')" 
                                 class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer transition transform hover:scale-[1.02] flex items-center p-4 gap-4">
                                <div class="w-20 h-20 flex-shrink-0">
                                    @if(!empty($product['image_path']))
                                        <img class="w-full h-full object-cover rounded-2xl" src="{{ asset('storage/' . $product['image_path']) }}" alt="{{ $product['name'] }}">
                                    @else
                                        <div class="w-full h-full bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 002-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <h4 class="font-bold text-lg leading-tight">{{ $product['name'] }}</h4>
                                    <p class="text-blue-600 font-black">RM {{ number_format($product['price'], 2) }}</p>
                                </div>
                                <div class="bg-blue-600 text-white p-2 rounded-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Yearly Choice -->
            @if(count($yearlyChoice) > 0)
                <div x-show="search === ''">
                    <h3 class="text-2xl font-black mb-4 px-2 flex items-center justify-between">
                        <span>👑 Yearly Choice</span>
                        <span class="text-xs font-bold bg-green-100 text-green-600 px-3 py-1 rounded-full uppercase">Best of {{ date('Y') }}</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-2">
                        @foreach($yearlyChoice as $id => $product)
                            <div @click="openModal('{{ $id }}', '{{ addslashes($product['name']) }}', {{ $product['price'] }}, '{{ $product['image_path'] ?? '' }}', '{{ addslashes($product['description'] ?? '') }}')" 
                                 class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer transition transform hover:scale-[1.02] flex items-center p-4 gap-4">
                                <div class="w-20 h-20 flex-shrink-0">
                                    @if(!empty($product['image_path']))
                                        <img class="w-full h-full object-cover rounded-2xl" src="{{ asset('storage/' . $product['image_path']) }}" alt="{{ $product['name'] }}">
                                    @else
                                        <div class="w-full h-full bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 002-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <h4 class="font-bold text-lg leading-tight">{{ $product['name'] }}</h4>
                                    <p class="text-green-600 font-black">RM {{ number_format($product['price'], 2) }}</p>
                                </div>
                                <div class="bg-green-600 text-white p-2 rounded-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Menu Categories -->
        <div class="max-w-7xl mx-auto px-4 pb-12">
            <!-- Navigation Chips -->
            <div class="sticky top-20 bg-gray-100/80 backdrop-blur-md z-40 py-3 mb-6 overflow-x-auto whitespace-nowrap scrollbar-hide">
                @foreach($categoriesWithProducts as $id => $category)
                    @if(count($category['products']) > 0)
                        <a href="#cat-{{ $id }}" 
                           x-show="hasVisibleProducts({{ json_encode($category['products']) }})"
                           class="inline-block px-5 py-2 mr-2 bg-white rounded-full shadow-sm text-gray-700 font-semibold hover:bg-blue-50 hover:text-blue-600 text-sm transition">{{ $category['name'] }}</a>
                    @endif
                @endforeach
            </div>

            @foreach($categoriesWithProducts as $catId => $category)
                @if(count($category['products']) > 0)
                    <div id="cat-{{ $catId }}" 
                         x-show="hasVisibleProducts({{ json_encode($category['products']) }})"
                         class="mb-10 scroll-mt-36">
                        <h3 class="text-2xl font-black mb-6 text-gray-900 flex items-center">
                            <span class="bg-blue-600 w-2 h-8 rounded-full mr-3"></span>
                            {{ $category['name'] }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($category['products'] as $prodId => $product)
                                <div x-show="filterProducts('{{ addslashes($product['name']) }}')"
                                     @click="openModal('{{ $prodId }}', '{{ addslashes($product['name']) }}', {{ $product['price'] }}, '{{ $product['image_path'] ?? '' }}', '{{ addslashes($product['description'] ?? '') }}')"
                                     class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex cursor-pointer transition hover:shadow-md hover:scale-[1.01] p-4">
                                    <div class="flex-grow">
                                        <h4 class="font-bold text-lg text-gray-900 mb-1">{{ $product['name'] }}</h4>
                                        <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ $product['description'] ?? 'No description available.' }}</p>
                                        <div class="flex items-baseline space-x-1">
                                            <span class="text-sm font-medium text-blue-600">RM</span>
                                            <span class="text-2xl font-black text-blue-600">{{ number_format($product['price'], 2) }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex-shrink-0 ml-4 flex flex-col items-center justify-center">
                                        @if(!empty($product['image_path']))
                                            <img class="h-20 w-20 object-cover rounded-xl shadow-inner mb-2" 
                                                 src="{{ asset('storage/' . $product['image_path']) }}" alt="{{ $product['name'] }}">
                                        @else
                                            <div class="h-20 w-20 bg-gray-50 rounded-xl mb-2 flex items-center justify-center text-gray-300">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 002-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                        <div class="bg-blue-600 text-white p-1 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Quantity Picker Modal -->
        <div x-show="isModalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[60] flex items-center justify-center px-4 bg-gray-900/60 backdrop-blur-sm"
             style="display: none;">
            
            <div @click.away="closeModal()" 
                 x-show="isModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden flex flex-col">
                
                <!-- Product Image in Modal -->
                <div class="h-48 w-full relative">
                    <template x-if="selectedProduct.image">
                        <img :src="'{{ asset('storage') }}/' + selectedProduct.image" :alt="selectedProduct.name" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!selectedProduct.image">
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">No Image</div>
                    </template>
                    <button @click="closeModal()" class="absolute top-4 right-4 bg-black/20 hover:bg-black/40 text-white p-2 rounded-full backdrop-blur-md transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6">
                    <h3 class="text-2xl font-black text-gray-900 mb-2" x-text="selectedProduct.name"></h3>
                    <p class="text-gray-500 text-sm mb-6" x-text="selectedProduct.description"></p>
                    
                    <div class="flex justify-between items-center mb-8">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Price</span>
                            <span class="text-xl font-black text-blue-600" x-text="'RM ' + (selectedProduct.price * qty).toFixed(2)"></span>
                        </div>
                        
                        <!-- Qty Selector -->
                        <div class="flex items-center bg-gray-100 rounded-2xl p-1 gap-2 border border-gray-200">
                            <button type="button" @click="if(qty > 1) qty--" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:text-blue-600 font-black text-xl transition active:scale-90">-</button>
                            <span class="w-8 text-center font-black text-lg" x-text="qty"></span>
                            <button type="button" @click="qty++" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:text-blue-600 font-black text-xl transition active:scale-90">+</button>
                        </div>
                    </div>

                    <form :action="'{{ url('cart/add') }}/' + selectedProduct.id" method="POST">
                        @csrf
                        <input type="hidden" name="quantity" :value="qty">
                        <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl text-lg font-black shadow-xl shadow-blue-200 transition transform active:scale-95 flex items-center justify-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-gray-900/90 backdrop-blur text-white px-8 py-4 rounded-full shadow-2xl z-50 flex items-center space-x-3">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif
</x-customer-layout>