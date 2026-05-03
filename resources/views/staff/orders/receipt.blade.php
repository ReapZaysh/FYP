<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $order['reference'] }}</title>
    <style>
        /* ── Thermal Paper Simulation (80mm width) ── */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .receipt {
            background: #fff;
            width: 302px; /* 80mm in pixels */
            padding: 16px 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.15);
        }

        /* Jagged top/bottom edge to mimic thermal paper tear */
        .receipt-edge {
            width: 100%;
            height: 10px;
            background-image: repeating-linear-gradient(
                90deg,
                transparent,
                transparent 6px,
                #f5f5f5 6px,
                #f5f5f5 10px
            );
        }
        .receipt-edge.top { border-radius: 4px 4px 0 0; transform: scaleY(-1); }
        .receipt-edge.bottom { border-radius: 0 0 4px 4px; }

        .center   { text-align: center; }
        .right    { text-align: right; }
        .bold     { font-weight: bold; }
        .small    { font-size: 10px; }
        .large    { font-size: 16px; }
        .xlarge   { font-size: 20px; }

        .divider  { border: none; border-top: 1px dashed #999; margin: 8px 0; }
        .divider-solid { border: none; border-top: 1px solid #000; margin: 8px 0; }

        .shop-name  { font-size: 22px; font-weight: bold; letter-spacing: 2px; }
        .tagline    { font-size: 10px; color: #555; margin-bottom: 2px; }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin: 4px 0;
            gap: 4px;
        }
        .item-name  { flex: 1; }
        .item-price { white-space: nowrap; }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 15px;
            font-weight: bold;
            margin: 6px 0;
        }

        .status-paid {
            display: inline-block;
            border: 2px solid #000;
            padding: 2px 12px;
            font-weight: bold;
            font-size: 13px;
            letter-spacing: 1px;
            margin-top: 4px;
        }

        /* ── Print Styles ── */
        @media print {
            body {
                background: none;
                padding: 0;
                margin: 0;
            }
            .receipt {
                box-shadow: none;
                width: 100%;
                padding: 4px 8px;
            }
            .no-print { display: none !important; }
        }

        /* ── Screen-only controls ── */
        .controls {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 16px;
        }
        .btn {
            padding: 10px 24px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-size: 13px;
        }
        .btn-print { background: #111; color: #fff; }
        .btn-close { background: #e5e7eb; color: #374151; }
    </style>
</head>
<body>

    <div>
        {{-- Receipt Paper --}}
        <div class="receipt">
            <div class="receipt-edge top"></div>

            <div style="padding: 8px 0;">
                {{-- Shop Header --}}
                <div class="center" style="margin-bottom: 8px;">
                    <div class="shop-name">BOSSKU HOUSE</div>
                    <div class="tagline">Delicious Food, Served Fresh</div>
                    <div class="tagline">Thank you for dining with us!</div>
                </div>

                <hr class="divider-solid">

                {{-- Order Info --}}
                <div style="margin: 6px 0;">
                    <div class="item-row small">
                        <span>Order Ref</span>
                        <span class="bold">#{{ $order['reference'] }}</span>
                    </div>
                    <div class="item-row small">
                        <span>Customer</span>
                        <span>{{ $order['customer_name'] ?? 'Guest' }}</span>
                    </div>
                    @if(!empty($order['table_number']))
                    <div class="item-row small">
                        <span>Table</span>
                        <span class="bold">{{ $order['table_number'] }}</span>
                    </div>
                    @endif
                    <div class="item-row small">
                        <span>Date</span>
                        <span>{{ \Carbon\Carbon::parse($order['created_at'] ?? now())->setTimezone('Asia/Kuala_Lumpur')->format('d/m/Y h:i A') }}</span>
                    </div>
                    @if(!empty($order['paid_at']))
                    <div class="item-row small">
                        <span>Paid At</span>
                        <span>{{ \Carbon\Carbon::parse($order['paid_at'])->setTimezone('Asia/Kuala_Lumpur')->format('d/m/Y h:i A') }}</span>
                    </div>
                    @endif
                </div>

                <hr class="divider">

                {{-- Items --}}
                <div style="margin: 6px 0;">
                    @foreach($order['items'] as $item)
                        <div class="item-row">
                            <span class="item-name">
                                {{ $item['quantity'] }}x {{ $item['name'] }}
                                @if(!empty($item['note']))
                                    <br><span class="small" style="color:#666;">  * {{ $item['note'] }}</span>
                                @endif
                            </span>
                            <span class="item-price">RM {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}</span>
                        </div>
                    @endforeach
                </div>

                @if(!empty($order['order_note']))
                <div class="small" style="margin: 4px 0; color:#555; border-left: 2px solid #ccc; padding-left: 6px;">
                    Note: {{ $order['order_note'] }}
                </div>
                @endif

                <hr class="divider-solid">

                {{-- Total --}}
                <div class="total-row">
                    <span>TOTAL</span>
                    <span>RM {{ number_format($order['total_amount'], 2) }}</span>
                </div>

                <hr class="divider">

                {{-- Payment Status --}}
                <div class="center" style="margin: 8px 0;">
                    @if(($order['payment_status'] ?? '') === 'paid')
                        <div class="status-paid">✓ PAID</div>
                    @else
                        <div class="status-paid">PENDING PAYMENT</div>
                    @endif
                </div>

                <hr class="divider">

                {{-- Footer --}}
                <div class="center small" style="margin-top: 8px; color: #555;">
                    <div>Powered by Bossku House POS</div>
                    <div style="margin-top: 4px; font-size: 9px; letter-spacing: 1px;">
                        *** THANK YOU - COME AGAIN ***
                    </div>
                </div>
            </div>

            <div class="receipt-edge bottom"></div>
        </div>

        {{-- Screen Controls (hidden when printing) --}}
        <div class="controls no-print">
            <button class="btn btn-print" onclick="window.print()">🖨️ Print Receipt</button>
            <button class="btn btn-close" onclick="window.close()">✕ Close</button>
        </div>
    </div>

    <script>
        // Auto-trigger the print dialog when this page opens
        window.addEventListener('load', () => {
            setTimeout(() => window.print(), 500);
        });
    </script>
</body>
</html>
