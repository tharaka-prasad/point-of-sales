@extends('layouts.master')

@section('title')
<h3>Invoice: {{ $sale->id }}</h3>
@endsection

@section('content')
<div class="container mt-4 d-flex justify-content-center">
    <div class="receipt p-3 shadow-sm">
        <!-- Company Info -->
        <h4 class="text-center mb-0">Ekrain & Technology</h4>
        <p class="text-center mb-1">Tel: 0114845935</p>

        <!-- Invoice Info -->
        <p class="text-center mb-2">
            Invoice #{{ $sale->id }} <br>
            {{ $created_at }} <!-- ✅ Only Date -->
        </p>
        <hr>

        <!-- Customer -->
        <p><strong>Customer:</strong> {{ $sale->member->name ?? 'Walk-in' }}</p>

        <!-- Products -->
        <table class="w-100 mb-2">
            <thead>
                <tr>
                    <th class="text-start">Item</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->details as $d)
                <tr>
                    <td>{{ $d->product->name ?? 'Deleted' }}</td>
                    <td class="text-end">{{ $d->amount }}</td>
                    <td class="text-end">{{ number_format($d->sub_total,2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <hr>

        <!-- Totals -->
        <p class="d-flex justify-content-between">
            <span>Subtotal</span>
            <span>{{ number_format($sale->total_price + $sale->discount,2) }}</span>
        </p>
        @if($sale->discount > 0)
        <p class="d-flex justify-content-between">
            <span>Discount</span>
            <span>-{{ number_format($sale->discount,2) }}</span>
        </p>
        @endif
        <p class="d-flex justify-content-between fw-bold">
            <span>Total</span>
            <span>{{ number_format($sale->total_price,2) }}</span>
        </p>
        <p class="d-flex justify-content-between">
            <span>Cash</span>
            <span>{{ number_format($sale->pay,2) }}</span>
        </p>
        <p class="d-flex justify-content-between">
            <span>Balance</span>
            <span>{{ number_format($sale->pay - $sale->total_price,2) }}</span>
        </p>

        <hr>
        <p class="text-center">*** Thank You! Come Again! ***</p>

        <div class="text-center mt-2 no-print">
            <button class="btn btn-primary btn-sm" onclick="window.print()">Print</button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.receipt {
    width: 80mm; /* ✅ thermal paper size */
    font-family: "Courier New", monospace;
    background: #fff;
    border: 1px dashed #aaa;
}
.receipt table {
    font-size: 14px;
}
.receipt th, .receipt td {
    padding: 2px 0;
}
@media print {
    body * {
        visibility: hidden;
    }
    .receipt, .receipt * {
        visibility: visible;
    }
    .receipt {
        margin: 0;
        padding: 0;
        border: none;
        width: 80mm;
    }
    .no-print { display: none !important; }
}
</style>
@endpush
