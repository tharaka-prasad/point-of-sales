@extends('layouts.master')

@section('title')
<h3>Invoice: {{ $sale->id }}</h3>
@endsection

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">Invoice #{{ $sale->id }}</h4>
                <small>{{ $created_at }}</small>
            </div>
            <div>
                <button class="btn btn-light" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>

        <div class="card-body">
            <!-- Customer Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6>Customer</h6>
                    <p>{{ $sale->member->name ?? 'Walk-in' }}</p>
                    @if($sale->member)
                    <p>{{ $sale->member->address }}</p>
                    <p>{{ $sale->member->phone }}</p>
                    @endif
                </div>
                <div class="col-md-6 text-end">
                    <h6>Payment Details</h6>
                    <p>Total: <strong>{{ number_format($sale->total_price,2) }}</strong></p>
                    <p>Cash: <strong>{{ number_format($sale->pay,2) }}</strong></p>
                    <p>Balance: <strong>{{ number_format($sale->pay - $sale->total_price,2) }}</strong></p>
                    <p>Status: <span class="badge bg-{{ $sale->status === 'complete' ? 'success' : 'warning' }}">{{ ucfirst($sale->status) }}</span></p>
                </div>
            </div>

            <!-- Product Table -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->details as $index => $d)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $d->product->name ?? 'Deleted Product' }}</td>
                            <td>{{ number_format($d->sale_price,2) }}</td>
                            <td>{{ $d->amount }}</td>
                            <td>{{ number_format($d->sub_total,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-4">
                <h5>Grand Total: <strong>{{ number_format($sale->total_price,2) }}</strong></h5>
            </div>
        </div>

        <div class="card-footer text-muted text-center">
            Thank you for your purchase!
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        .card-footer, .btn { display: none !important; }
    }
    .card {
        border-radius: 15px;
    }
</style>
@endpush
