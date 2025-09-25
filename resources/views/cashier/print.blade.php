<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <style>
        body {
            width: 72mm;
            font-family: Arial;
            font-size: 11px;
            margin: 0;
            padding: 5px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .items td,
        .items th {
            padding: 3px 0;
        }

        .items .name {
            width: 50%;
        }

        .items .qty,
        .items .price,
        .items .total {
            text-align: right;
            width: 16%;
        }

        .totals td {
            padding: 3px 0;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
        }

        @media print {
            #printBtn {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="center">
        {{-- <div class="line">
            <img src="{{ asset('admin/images/logo-2025-09-18074601.jpg') }}" alt="Logo"
                class="brand-image img-circle elevation-3" style="opacity: 0.8; width: 60px; height: 60px;">
        </div> --}}
        <span class="bold" style="font-size: 18px;">Ekrain Technologies & Solutions (Pvt) Ltd</span><br>
        No.118/115, Kandewaththa Road, Nugegoda, Sri Lanka <br>
        Tel: 011-4845935
    </div>

    <div class="line"></div>

    <div>
        Date: {{ $sale->created_at->format('Y-m-d') }} &nbsp;&nbsp;&nbsp;
        Time: {{ $sale->created_at->format('H:i') }}<br>
        Receipt No: {{ str_pad($sale->id, 8, '0', STR_PAD_LEFT) }}<br>
        Cashier: {{ $sale->cashier->name ?? 'N/A' }}
    </div>

    <div class="line"></div>

    <table class="items">
        <tr>
            <th class="name">Item</th>
            <th class="qty">Qty</th>
            <th class="price">Unit Price</th>
            <th class="total">Total</th>
        </tr>
        @foreach ($sale->items as $item)
            <tr>
                <td class="name">{{ $item->product->name ?? 'N/A' }}</td>
                <td class="qty">{{ $item->amount }}</td>
                <td class="price">Rs. {{ number_format($item->sale_price, 2) }}</td>
                <td class="total">Rs. {{ number_format($item->sub_total, 2) }}</td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td style="text-align:right;">Rs. {{ number_format($subtotal, 2) }}</td>
        </tr>
        <tr>
            <td>Discount</td>
            <td style="text-align:right;">− Rs. {{ number_format($discount, 2) }}</td>
        </tr>
        <tr class="bold">
            <td>Total</td>
            <td style="text-align:right;">Rs. {{ number_format($total, 2) }}</td>
        </tr>
        <tr>
            <td>Paid</td>
            <td style="text-align:right;">Rs. {{ number_format($paid, 2) }}</td>
        </tr>
        <tr>
            <td>Change</td>
            <td style="text-align:right;">Rs. {{ number_format($change, 2) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="footer">
        Thank you for shopping at Ekrain Technologies!<br>
        Prices include government taxes.<br>
        * Prices and items may vary by branch.
    </div>

    <div class="center" style="margin-top:10px;">
        <button id="printBtn" onclick="window.print()">🖨️ Print Receipt (F9)</button>
    </div>
    <script>
        function printReceipt() {
            window.print();
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'F9') {
                event.preventDefault();
                printReceipt();
            }
        });

        // Uncomment this to auto-print on page load:
        // window.onload = printReceipt;
    </script>
</body>

</html>
