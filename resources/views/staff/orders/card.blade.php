<div class="bg-white rounded-3xl shadow-sm border border-gray-50 p-6 group hover:shadow-md transition-all relative overflow-hidden">
    <div class="absolute top-0 left-0 w-1.5 h-full {{ $order['status'] == 'submitted' ? 'bg-blue-500' : 'bg-amber-500' }}"></div>
    
    <div class="flex justify-between items-start mb-4">
        <div>
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">#{{ $order['reference'] }}</span>
            <h4 class="font-bold text-gray-900 leading-tight">{{ $order['customer_name'] ?? 'Guest Customer' }}</h4>
        </div>
        <span class="text-xs font-bold text-gray-400">{{ \Carbon\Carbon::parse($order['created_at'])->format('H:i') }}</span>
    </div>

    @if(isset($order['table_number']))
        <div class="mb-4">
            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-black rounded-lg uppercase tracking-wider">Table {{ $order['table_number'] }}</span>
        </div>
    @endif

    <div class="space-y-3 mb-6">
        @foreach($order['items'] as $item)
            <div class="flex justify-between items-start text-sm">
                <div class="flex-1">
                    <span class="font-bold text-gray-800">{{ $item['quantity'] }}x</span>
                    <span class="text-gray-600 ml-1">{{ $item['name'] }}</span>
                    @if(!empty($item['note']))
                        <p class="text-[10px] text-premium-brown italic mt-0.5">"{{ $item['note'] }}"</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if(!empty($order['order_note']))
        <div class="mb-6 p-3 bg-blue-50/50 rounded-2xl border border-blue-100 text-blue-800 text-xs">
            <p class="font-bold mb-1 uppercase tracking-tight">Special Instruction:</p>
            {{ $order['order_note'] }}
        </div>
    @endif

    <div class="flex justify-between items-center pt-4 border-t border-gray-50">
        <span class="text-lg font-black text-gray-900">RM {{ number_format($order['total_amount'], 2) }}</span>

        <div class="flex items-center gap-2">
            <form action="{{ route('staff.orders.update', $order['reference']) }}" method="POST">
                @csrf
                @method('PATCH')
                @if($order['status'] == 'submitted')
                    <button name="status" value="in_progress"
                        class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold py-2 px-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all">
                        Start Prep
                    </button>
                @elseif($order['status'] == 'in_progress')
                    <button name="status" value="completed"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold py-2 px-4 rounded-xl shadow-lg shadow-emerald-500/20 transition-all">
                        Complete
                    </button>
                @endif
            </form>

            @if($order['status'] != 'completed')
                <form action="{{ route('staff.orders.update', $order['reference']) }}" method="POST"
                    onsubmit="return confirm('Cancel this order?');">
                    @csrf
                    @method('PATCH')
                    <button name="status" value="canceled" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-all" title="Cancel Order">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>