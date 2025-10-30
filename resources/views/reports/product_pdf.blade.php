<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Report</title>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .text-center {
            text-align: center;
        }

        .table {
            width: 100%;
            margin-top: 20px;
            font-size: 12px;
            border-collapse: collapse;
            background-color: #fff;
            border: 1px solid #333;
        }

        .table th,
        .table td {
            padding: 10px 12px;
            border: 1px solid #333;
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table-striped tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        h4 {
            color: #555;
            margin-top: 5px;
            font-size: 14px;
            display: inline-block;
        }

        h3 {
            font-size: 22px;
            margin-bottom: 5px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .footer {
            margin-top: 30px;
            font-size: 11px;
            text-align: right;
            color: #666;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>Ekrain Technologies & Solutions (Pvt) Ltd</h3>
        <h4>Date: {{ now()->format('Y-m-d H:i:s') }}</h4>
    </div>

    <table class="table table-striped text-center">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th>Code</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Cost</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>{{ number_format($product->price, 2) }}</td>
                    <td>{{ number_format($product->sell_price, 2) }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on: {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>

</html>
