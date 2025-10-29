<!DOCTYPE html>
<html>
<head>
    <title>Supplier Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Supplier Report</h2>
    <p>Date: {{ $first_date }} to {{ $last_date }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Supplier Name</th>
                <th>Company Name</th>
                <th>Category</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $supplier)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $supplier->supplier_name }}</td>
                    <td>{{ $supplier->company_name }}</td>
                    <td>{{ $supplier->category->name ?? '-' }}</td>
                    <td>{{ $supplier->phone }}</td>
                    <td>{{ $supplier->address }}</td>
                    <td>{{ $supplier->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
