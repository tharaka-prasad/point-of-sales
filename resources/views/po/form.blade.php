@extends('layouts.master')

@section('title')
    <h3 class="mb-0">{{ $menu }}</h3>
@endsection

@section('breadcumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('po.index') }}">PO</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $menu }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5>{{ $menu }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('po.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>PO Number</label>
                    <input type="text" name="po_number" class="form-control" value="{{ $nextPoNumber ?? old('po_number') ?? 'PO-1000' }}" readonly>
                </div>
                <div class="form-group">
                    <label>Company</label>
                    <input type="text" name="company_name" class="form-control">
                </div>
                <div class="form-group">
                    <label>Supplier</label>
                    <input type="text" name="supplier_name" class="form-control">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity" class="form-control">
                </div>
                <div class="form-group">
                    <label>Rate</label>
                    <input type="number" name="rate" class="form-control">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="draft">Draft</option>
                        <option value="complete">Complete</option>
                        <option value="pending">Pending</option>
                        <option value="reject">Reject</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Save</button>
                <button type="reset" class="btn btn-warning mt-3">Clear</button>
                <a href="{{ route('po.index') }}" class="btn btn-secondary mt-3">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
