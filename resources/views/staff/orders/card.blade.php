<div
    class="bg-white rounded shadow p-4 mb-4 border-l-4 {{ $order['status'] == 'submitted' ? 'border-blue-500' : 'border-yellow-500' }}">
    <div class="flex justify-between items-start mb-2">
        <span class="font-bold text-lg">#{{ $order['reference'] }}</span>
        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($order['created_at'])->format('H:i') }}</span>
    </div>
    <div class="text-sm font-semibold mb-2">
        {{ $order['customer_name'] ?? 'Guest' }}
    </div>
    <ul class="text-sm mb-4 border-t border-b py-2 space-y-1">
        @foreach($order['items'] as $item)
            <li class="flex justify-between">
                <span>{{ $item['quantity'] }}x {{ $item['name'] }}</span>
                @if(!empty($item['note']))
                    <span class="text-xs text-gray-500 italic">({{ $item['note'] }})</span>
                @endif
            </li>
        @endforeach
    </ul>
    <div class="flex justify-between items-center mt-2">
        <span class="font-bold">RM {{ number_format($order['total_amount'], 2) }}</span>

        <form action="{{ route('staff.orders.update', $order['reference']) }}" method="POST">
            @csrf
            @method('PATCH')
            @if($order['status'] == 'submitted')
                <button name="status" value="in_progress"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold py-1 px-3 rounded">
                    Start Prep
                </button>
            @elseif($order['status'] == 'in_progress')
                <button name="status" value="completed"
                    class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-1 px-3 rounded">
                    Complete
                </button>
            @endif
        </form>
    </div>
    @if($order['status'] != 'completed')
        <div class="mt-2 text-right">
            <form action="{{ route('staff.orders.update', $order['reference']) }}" method="POST"
                onsubmit="return confirm('Cancel this order?');">
                @csrf
                @method('PATCH')
                <button name="status" value="canceled" class="text-xs text-red-500 hover:underline">Cancel Order</button>
            </form>
        </div>
    @endif
</div>