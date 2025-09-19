@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Receipt</h4>
    <p><strong>Invoice:</strong> #{{ $sale->id }}</p>
    <p><strong>Date:</strong> {{ $sale->created_at->format('d-m-Y H:i') }}</p>
    <p><strong>Customer:</strong> {{ $sale->member->name ?? 'Walk-in' }}</p>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->details as $detail)
            <tr>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->amount }}</td>
                <td>{{ number_format($detail->sale_price, 2) }}</td>
                <td>{{ number_format($detail->sub_total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h5 class="text-end">Total: {{ number_format($sale->total_price, 2) }}</h5>

    <script>
        window.print(); // auto print on load
    </script>
</div>
@endsection
