@props(['href', 'icon', 'label', 'active' => false])

@php
$icons = [
    'dashboard' => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>',
    'categories' => '<path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path><path d="M7 7v10M11 7v10M15 7v10"></path>',
    'products' => '<path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>',
    'analytics' => '<path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>',
    'reviews' => '<path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.783.57-1.837-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>',
    'orders' => '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>',
    'history' => '<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
];
@endphp

<a href="{{ $href }}" 
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group {{ $active ? 'sidebar-item-active shadow-lg shadow-premium-brown/30' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}"
   :class="!sidebarOpen && 'lg:px-2 lg:justify-center'"
>
    <svg class="w-6 h-6 shrink-0 {{ $active ? 'text-white' : 'text-gray-400 group-hover:text-premium-brown' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {!! $icons[(string)$icon] ?? '' !!}
    </svg>
    <span class="font-medium truncate transition-all duration-300" x-show="sidebarOpen" x-transition>{{ $label }}</span>
</a>
