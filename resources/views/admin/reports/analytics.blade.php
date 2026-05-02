<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #9c6644; padding-bottom: 20px; }
        .header h1 { color: #9c6644; margin: 0; font-size: 24px; }
        .header p { margin: 5px 0 0; color: #666; }
        
        .section-title { font-size: 18px; font-weight: bold; margin: 25px 0 15px; color: #444; border-left: 4px solid #9c6644; padding-left: 10px; }
        
        .stats-grid { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .stats-box { background: #f9f7f4; padding: 15px; border-radius: 8px; text-align: center; border: 1px solid #eee; }
        .stats-label { font-size: 10px; text-transform: uppercase; color: #888; font-weight: bold; margin-bottom: 5px; }
        .stats-value { font-size: 20px; font-weight: bold; color: #9c6644; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #f4f4f4; text-align: left; padding: 10px; font-size: 12px; text-transform: uppercase; color: #666; }
        td { padding: 10px; border-bottom: 1px solid #eee; font-size: 13px; }
        
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .footer { margin-top: 50px; font-size: 10px; color: #aaa; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BOSSKU HOUSE</h1>
        <p>{{ $title }}</p>
        <p>Generated on {{ date('d M Y, H:i') }}</p>
    </div>

    <div class="section-title">Monthly Summary</div>
    <table class="stats-grid">
        <tr>
            <td width="33%">
                <div class="stats-box">
                    <div class="stats-label">Total Revenue</div>
                    <div class="stats-value">RM {{ number_format($totalRevenue, 2) }}</div>
                </div>
            </td>
            <td width="33%">
                <div class="stats-box">
                    <div class="stats-label">Total Orders</div>
                    <div class="stats-value">{{ $totalOrders }}</div>
                </div>
            </td>
            <td width="33%">
                <div class="stats-box">
                    <div class="stats-label">Avg. Order Value</div>
                    <div class="stats-value">RM {{ number_format($avgOrderValue, 2) }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Top Performing Products</div>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Product Name</th>
                <th class="text-right">Units Sold</th>
                <th class="text-right">Total Sales</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topSellers as $id => $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="font-bold">{{ $product['name'] }}</td>
                    <td class="text-right">{{ $product['quantity'] }}</td>
                    <td class="text-right font-bold">RM {{ number_format($product['total'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Category Performance</div>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th class="text-right">Revenue Contribution</th>
                <th class="text-right">Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categoryData as $name => $revenue)
                <tr>
                    <td class="font-bold">{{ $name }}</td>
                    <td class="text-right">RM {{ number_format($revenue, 2) }}</td>
                    <td class="text-right">{{ number_format(($revenue / $totalRevenue) * 100, 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} Bossku House Ordering System. This is a computer-generated document.
    </div>
</body>
</html>
