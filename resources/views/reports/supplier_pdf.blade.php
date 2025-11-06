<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Report</title>

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
            border: 1px solid #333; /* Outer border for table */
        }

        .table th,
        .table td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
            border: 1px solid #333; /* Border around every cell */
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table-striped tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        .table-striped tbody tr:hover {
            background-color: #f1f1f1;
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

        .date-line {
            display: inline-block;
            white-space: nowrap; /* ✅ keep date range in one line */
        }

        .footer {
            margin-top: 30px;
            font-size: 11px;
            text-align: right;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>Ekrain Technologies & Solutions (Pvt) Ltd</h3>
        <h4 class="date-line">
            Date: {{ now()->format('Y-m-d H:i:s') }}
        </h4>
    </div>

    <table class="table table-striped text-center">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th>Supplier Name</th>
                <th>Company Name</th>
                <th>Category</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Since</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $supplier)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $supplier->supplier_name }}</td>
                    <td>{{ $supplier->company_name }}</td>
                    <td>{{ $supplier->category->name ?? '-' }}</td>
                    <td>{{ $supplier->phone }}</td>
                    <td>{{ $supplier->address }}</td>
                    <td>{{ $supplier->created_at->format('Y-m-d') }}</td> {{-- ✅ only date --}}
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on: {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>

</html>
