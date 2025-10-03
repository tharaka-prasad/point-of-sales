@extends('layouts.master')

@section('title')
    <h3 class="mb-0">Create Purchase Order (PO)</h3>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">New Purchase Order</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('po.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="mb-3">
                            <label for="po_number" class="form-label">PO Number</label>
                            <input type="text" name="po_number" id="po_number" class="form-control"
                                value="{{ $nextPoNumber }}" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="purchase_company" class="form-label">Company</label>
                            <input type="text" class="form-control" id="purchase_company" name="purchase_company"
                                value="{{ old('purchase_company') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="supplier_id" class="form-label">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-control" required>
                                <option value="">-- Select Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="2">{{ old('description') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="contact_no" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="contact_no" name="contact_no"
                                value="{{ old('contact_no') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity"
                                value="{{ old('quantity') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label for="rate" class="form-label">Rate</label>
                            <input type="number" class="form-control" id="rate" name="rate" step="0.01"
                                value="{{ old('rate') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="draft">Draft</option>
                                <option value="pending">Pending</option>
                                <option value="complete">Complete</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Create PO</button>
                        <a href="{{ route('po.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
