<x-customer-layout>
    <div x-data="{ 
        search: '',
        isModalOpen: false,
        activeTab: 'order',
        selectedProduct: { id: '', name: '', price: 0, image: '', description: '', average_rating: 0, review_count: 0, reviews: [] },
        qty: 1,
        toastMessage: '',
        showToast: false,

        openModal(id, name, price, image, description, average_rating, review_count, reviews) {
            this.selectedProduct = { id, name, price, image, description, average_rating, review_count, reviews };
            this.qty = 1;
            this.activeTab = 'order';
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
        },
        async submitToCart() {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('quantity', this.qty);

            try {
                const response = await fetch(`/cart/add/${this.selectedProduct.id}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.closeModal();
                    
                    window.dispatchEvent(new CustomEvent('cart-updated', { 
                        detail: { count: data.cart_count }
                    }));

                    this.toastMessage = data.message;
                    this.showToast = true;
                    setTimeout(() => this.showToast = false, 3000);
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
            }
        }
    }" @keydown.escape="closeModal()">
        
        @if(session('table_number'))
        <div class="bg-blue-600 text-white px-4 py-2 text-center text-sm font-bold shadow-md relative z-50">
            <div class="max-w-7xl mx-auto flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                You are ordering for Table: {{ session('table_number') }}
            </div>
        </div>
        @endif

        <!-- Search Section -->
        <div class="bg-white shadow-sm sticky top-0 z-50 py-4 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="relative mb-4">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input x-model="search" type="text" 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-full leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out" 
                        placeholder="Search for wings, drinks, or anything...">
                </div>

                <!-- Quick Categories -->
                <div class="flex overflow-x-auto space-x-2 pb-1 scrollbar-hide">
                    @foreach($categoriesWithProducts as $id => $category)
                        @if(count($category['products']) > 0)
                            <a href="#cat-{{ $id }}" 
                               x-show="hasVisibleProducts({{ json_encode($category['products']) }})"
                               class="flex-shrink-0 px-4 py-2 bg-blue-50 text-blue-600 rounded-full text-xs font-bold transition hover:bg-blue-100">{{ $category['name'] }}</a>
                        @endif
                    @endforeach
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
                            <div @click="openModal('{{ $id }}', '{{ addslashes($product['name']) }}', {{ $product['price'] }}, '{{ $product['image_path'] ?? '' }}', '{{ addslashes($product['description'] ?? '') }}', {{ $product['average_rating'] ?? 0 }}, {{ $product['review_count'] ?? 0 }}, {{ json_encode($product['reviews'] ?? []) }})" 
                                 class="flex-shrink-0 w-64 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer transition transform hover:scale-[1.02]">
                                @if(!empty($product['image_path']))
                                    <img class="h-40 w-full object-cover" src="{{ str_starts_with($product['image_path'], 'http') ? $product['image_path'] : asset('storage/' . $product['image_path']) }}" alt="{{ $product['name'] }}">
                                @else
                                    <div class="h-40 w-full bg-gray-100 flex items-center justify-center text-gray-400">No Image</div>
                                @endif
                                <div class="p-4">
                                    <h4 class="font-bold text-lg mb-1">{{ $product['name'] }}</h4>
                                    
                                    @if(isset($product['review_count']) && $product['review_count'] > 0)
                                    <div class="flex items-center text-xs text-yellow-500 mb-2 font-bold">
                                        <span>★ {{ $product['average_rating'] }}</span>
                                        <span class="text-gray-400 ml-1">({{ $product['review_count'] }})</span>
                                    </div>
                                    @endif

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
                            <div @click="openModal('{{ $id }}', '{{ addslashes($product['name']) }}', {{ $product['price'] }}, '{{ $product['image_path'] ?? '' }}', '{{ addslashes($product['description'] ?? '') }}', {{ $product['average_rating'] ?? 0 }}, {{ $product['review_count'] ?? 0 }}, {{ json_encode($product['reviews'] ?? []) }})" 
                                 class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer transition transform hover:scale-[1.02] flex items-center p-4 gap-4">
                                <div class="w-20 h-20 flex-shrink-0">
                                    @if(!empty($product['image_path']))
                                        <img class="w-full h-full object-cover rounded-2xl" src="{{ str_starts_with($product['image_path'], 'http') ? $product['image_path'] : asset('storage/' . $product['image_path']) }}" alt="{{ $product['name'] }}">
                                    @else
                                        <div class="w-full h-full bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 002-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <h4 class="font-bold text-lg leading-tight">{{ $product['name'] }}</h4>
                                    
                                    @if(isset($product['review_count']) && $product['review_count'] > 0)
                                    <div class="flex items-center text-xs text-yellow-500 mb-1 font-bold">
                                        <span>★ {{ $product['average_rating'] }}</span>
                                        <span class="text-gray-400 ml-1">({{ $product['review_count'] }})</span>
                                    </div>
                                    @endif

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
                            <div @click="openModal('{{ $id }}', '{{ addslashes($product['name']) }}', {{ $product['price'] }}, '{{ $product['image_path'] ?? '' }}', '{{ addslashes($product['description'] ?? '') }}', {{ $product['average_rating'] ?? 0 }}, {{ $product['review_count'] ?? 0 }}, {{ json_encode($product['reviews'] ?? []) }})" 
                                 class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer transition transform hover:scale-[1.02] flex items-center p-4 gap-4">
                                <div class="w-20 h-20 flex-shrink-0">
                                    @if(!empty($product['image_path']))
                                        <img class="w-full h-full object-cover rounded-2xl" src="{{ str_starts_with($product['image_path'], 'http') ? $product['image_path'] : asset('storage/' . $product['image_path']) }}" alt="{{ $product['name'] }}">
                                    @else
                                        <div class="w-full h-full bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 002-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <h4 class="font-bold text-lg leading-tight">{{ $product['name'] }}</h4>
                                    
                                    @if(isset($product['review_count']) && $product['review_count'] > 0)
                                    <div class="flex items-center text-xs text-yellow-500 mb-1 font-bold">
                                        <span>★ {{ $product['average_rating'] }}</span>
                                        <span class="text-gray-400 ml-1">({{ $product['review_count'] }})</span>
                                    </div>
                                    @endif

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
                                     @click="openModal('{{ $prodId }}', '{{ addslashes($product['name']) }}', {{ $product['price'] }}, '{{ $product['image_path'] ?? '' }}', '{{ addslashes($product['description'] ?? '') }}', {{ $product['average_rating'] ?? 0 }}, {{ $product['review_count'] ?? 0 }}, {{ json_encode($product['reviews'] ?? []) }})"
                                     class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex cursor-pointer transition hover:shadow-md hover:scale-[1.01] p-4">
                                    <div class="flex-grow">
                                        <h4 class="font-bold text-lg text-gray-900 mb-1">{{ $product['name'] }}</h4>
                                        
                                        @if(isset($product['review_count']) && $product['review_count'] > 0)
                                        <div class="flex items-center text-xs text-yellow-500 mb-1 font-bold">
                                            <span>★ {{ $product['average_rating'] }}</span>
                                            <span class="text-gray-400 ml-1">({{ $product['review_count'] }})</span>
                                        </div>
                                        @endif

                                        <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ $product['description'] ?? 'No description available.' }}</p>
                                        <div class="flex items-baseline space-x-1">
                                            <span class="text-sm font-medium text-blue-600">RM</span>
                                            <span class="text-2xl font-black text-blue-600">{{ number_format($product['price'], 2) }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex-shrink-0 ml-4 flex flex-col items-center justify-center">
                                        @if(!empty($product['image_path']))
                                            <img class="h-20 w-20 object-cover rounded-xl shadow-inner mb-2" 
                                                 src="{{ str_starts_with($product['image_path'], 'http') ? $product['image_path'] : asset('storage/' . $product['image_path']) }}" alt="{{ $product['name'] }}">
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
                        <img :src="selectedProduct.image.startsWith('http') ? selectedProduct.image : '{{ asset('storage') }}/' + selectedProduct.image" :alt="selectedProduct.name" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!selectedProduct.image">
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">No Image</div>
                    </template>
                    <button @click="closeModal()" class="absolute top-4 right-4 bg-black/20 hover:bg-black/40 text-white p-2 rounded-full backdrop-blur-md transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6">
                    <div class="flex border-b border-gray-100 mb-4">
                        <button @click="activeTab = 'order'" :class="{'border-blue-600 text-blue-600': activeTab === 'order', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'order'}" class="flex-1 py-2 font-bold text-center border-b-2 transition">Order</button>
                        <button @click="activeTab = 'reviews'" :class="{'border-blue-600 text-blue-600': activeTab === 'reviews', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'reviews'}" class="flex-1 py-2 font-bold text-center border-b-2 transition">
                            Reviews <span x-show="selectedProduct.review_count > 0" x-text="'(' + selectedProduct.review_count + ')'" class="text-xs ml-1"></span>
                        </button>
                    </div>

                    <!-- Order Tab -->
                    <div x-show="activeTab === 'order'">
                        <h3 class="text-2xl font-black text-gray-900 mb-1" x-text="selectedProduct.name"></h3>
                        <div class="flex items-center text-xs text-yellow-500 mb-2 font-bold" x-show="selectedProduct.review_count > 0">
                            <span x-text="'★ ' + selectedProduct.average_rating"></span>
                        </div>
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

                        <form @submit.prevent="submitToCart">
                            <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl text-lg font-black shadow-xl shadow-blue-200 transition transform active:scale-95 flex items-center justify-center gap-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Add to Cart
                            </button>
                        </form>
                    </div>

                    <!-- Reviews Tab -->
                    <div x-show="activeTab === 'reviews'" class="max-h-[60vh] overflow-y-auto scrollbar-hide -mx-6 px-6">
                        
                        <!-- Review Form -->
                        <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-100">
                            <h4 class="font-bold text-gray-900 mb-3 text-sm">Leave a Review</h4>
                            <form :action="'/reviews/' + selectedProduct.id" method="POST">
                                @csrf
                                <div class="mb-3" x-data="{ rating: 0, hoverRating: 0 }">
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Rating</label>
                                    <div class="flex gap-2" @mouseleave="hoverRating = 0">
                                        <template x-for="i in 5">
                                            <label class="cursor-pointer" @mouseenter="hoverRating = i">
                                                <input type="radio" name="rating" :value="i" x-model="rating" class="hidden" required>
                                                <svg class="w-6 h-6 transition" 
                                                     :class="(hoverRating >= i || (!hoverRating && rating >= i)) ? 'text-yellow-400' : 'text-gray-300'" 
                                                     fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="customer_name" placeholder="Your Name (Optional)" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="mb-3">
                                    <textarea name="comment" placeholder="Tell us what you think..." rows="2" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_anonymous" value="1" class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-bold text-gray-600">Review Anonymously</span>
                                    </label>
                                    <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-gray-800 transition">Submit</button>
                                </div>
                            </form>
                        </div>

                        <!-- Review List -->
                        <div class="space-y-4">
                            <template x-if="selectedProduct.reviews.length === 0">
                                <p class="text-center text-gray-500 text-sm py-4 italic">No reviews yet. Be the first!</p>
                            </template>
                            
                            <template x-for="review in selectedProduct.reviews">
                                <div class="border-b border-gray-100 pb-4 last:border-0">
                                    <div class="flex justify-between items-start mb-1">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-sm text-gray-900" x-text="review.customer_name"></span>
                                            <span class="text-xs text-yellow-500 font-bold" x-text="'★ '.repeat(review.rating) + '☆ '.repeat(5-review.rating)"></span>
                                        </div>
                                        <span class="text-[10px] text-gray-400 font-mono" x-text="review.code"></span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1" x-text="review.comment"></p>
                                </div>
                            </template>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Alpine Dynamic Toast -->
        <div x-show="showToast" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4"
             style="display: none;"
             class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-gray-900/90 backdrop-blur text-white px-8 py-4 rounded-full shadow-2xl z-[100] flex items-center space-x-3">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold" x-text="toastMessage"></span>
        </div>

    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-gray-900/90 backdrop-blur text-white px-8 py-4 rounded-full shadow-2xl z-[100] flex items-center space-x-3">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif


</x-customer-layout>