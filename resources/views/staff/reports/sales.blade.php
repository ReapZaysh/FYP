<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #1a56db; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f9fafb; color: #4b5563; font-weight: bold; text-align: left; border: 1px solid #e5e7eb; padding: 10px; text-transform: uppercase; font-size: 10px; }
        td { border: 1px solid #e5e7eb; padding: 10px; vertical-align: top; }
        .total-row { font-weight: bold; background-color: #f3f4f6; }
        .footer { text-align: right; margin-top: 30px; font-size: 14px; font-weight: bold; }
        .status-completed { color: green; font-weight: bold; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bossku House</h1>
        <h2>{{ $title }}</h2>
        <p>Generated on: {{ now()->format('d M Y, h:i A') }}</p>
        <p>Period: {{ $period }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Table</th>
                <th>Items</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $order)
                <tr>
                    <td>#{{ $order['reference'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($order['updated_at'])->format('d M Y, h:i A') }}</td>
                    <td>{{ $order['customer_name'] ?? 'Guest' }}</td>
                    <td>{{ $order['table_number'] ?? 'Counter' }}</td>
                    <td>
                        @foreach($order['items'] as $item)
                            {{ $item['quantity'] }}x {{ $item['name'] }}{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </td>
                    <td class="text-right">RM {{ number_format($order['total_amount'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Total Sales: RM {{ number_format($totalSales, 2) }}
    </div>
</body>
</html>
