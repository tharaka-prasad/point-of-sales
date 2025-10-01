@extends('layouts.master')

@section('title')
    <h3 class="mb-0">Edit GRN: {{ $grn->grn_no }}</h3>
@endsection

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Edit GRN</h4>
            <a href="{{ route('grn.index') }}" class="btn btn-secondary">Back to List</a>
        </div>

        <div class="card-body">
            <form action="{{ route('grn.update', $grn->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="grn_no" class="form-label">GRN No</label>
                    <input type="text" name="grn_no" id="grn_no" class="form-control" value="{{ old('grn_no', $grn->grn_no) }}" required>
                </div>

                <div class="mb-3">
                    <label for="grn_date" class="form-label">GRN Date</label>
                    <input type="date" name="grn_date" id="grn_date" class="form-control" value="{{ old('grn_date', $grn->grn_date->format('Y-m-d')) }}" required>
                </div>

                <div class="mb-3">
                    <label for="supplier" class="form-label">Supplier</label>
                    <input type="text" name="supplier" id="supplier" class="form-control" value="{{ old('supplier', $grn->supplier) }}" required>
                </div>

                <div class="mb-3">
                    <label for="po_no" class="form-label">PO No</label>
                    <input type="text" name="po_no" id="po_no" class="form-control" value="{{ old('po_no', $grn->po_no) }}">
                </div>

                <div class="mb-3">
                    <label for="invoice_no" class="form-label">Invoice No</label>
                    <input type="text" name="invoice_no" id="invoice_no" class="form-control" value="{{ old('invoice_no', $grn->invoice_no) }}">
                </div>

                <div class="mb-3">
                    <label for="department" class="form-label">Department</label>
                    <input type="text" name="department" id="department" class="form-control" value="{{ old('department', $grn->department) }}">
                </div>

                <div class="mb-3">
                    <label for="prepared_by" class="form-label">Prepared By</label>
                    <input type="text" name="prepared_by" id="prepared_by" class="form-control" value="{{ old('prepared_by', $grn->prepared_by) }}">
                </div>

                <div class="mb-3">
                    <label for="grand_total" class="form-label">Grand Total</label>
                    <input type="number" step="0.01" name="grand_total" id="grand_total" class="form-control" value="{{ old('grand_total', $grn->grand_total) }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Update GRN</button>
            </form>
        </div>
    </div>
</div>
@endsection
