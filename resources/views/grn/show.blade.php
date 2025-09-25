@extends('layouts.master')

@section('title')
    <h3 class="mb-0">GRN Details: {{ $grn->grn_no }}</h3>
@endsection

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">GRN Details</h4>
            <a href="{{ route('grn.index') }}" class="btn btn-secondary">Back to List</a>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>GRN No</th>
                    <td>{{ $grn->grn_no }}</td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{{ \Carbon\Carbon::parse($grn->grn_date)->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <th>Supplier</th>
                    <td>{{ $grn->supplier }}</td>
                </tr>
                <tr>
                    <th>PO No</th>
                    <td>{{ $grn->po_no }}</td>
                </tr>
                <tr>
                    <th>Invoice No</th>
                    <td>{{ $grn->invoice_no }}</td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td>{{ $grn->department }}</td>
                </tr>
                <tr>
                    <th>Prepared By</th>
                    <td>{{ $grn->prepared_by }}</td>
                </tr>
                <tr>
                    <th>Grand Total</th>
                    <td>{{ number_format($grn->grand_total, 2) }}</td>
                </tr>
            </table>

            <div class="mt-3">
                <a href="{{ route('grn.edit', $grn->id) }}" class="btn btn-warning">Edit GRN</a>
                <a href="{{ route('grn.download', $grn->id) }}" class="btn btn-success">Download PDF</a>
            </div>
        </div>
    </div>
</div>
@endsection
