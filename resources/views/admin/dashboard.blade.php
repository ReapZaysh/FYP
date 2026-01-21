<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Manage Categories -->
                <a href="{{ route('admin.categories.index') }}"
                    class="block p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="text-gray-900">
                        <h3 class="text-lg font-bold mb-2">Manage Categories</h3>
                        <p class="text-gray-600">Add, edit, or remove menu categories.</p>
                    </div>
                </a>

                <!-- Manage Products -->
                <a href="{{ route('admin.products.index') }}"
                    class="block p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="text-gray-900">
                        <h3 class="text-lg font-bold mb-2">Manage Products</h3>
                        <p class="text-gray-600">Update menu items, prices, and availability.</p>
                    </div>
                </a>
                <!-- Sales Analytics -->
                <a href="{{ route('admin.analytics') }}"
                    class="block p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="text-gray-900">
                        <h3 class="text-lg font-bold mb-2">Sales Analytics</h3>
                        <p class="text-gray-600">View detailed sales reports and charts.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>