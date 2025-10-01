@extends('layouts.master')

@section('title')
<h3 class="mb-0">{{ $menu }}</h3>
@endsection

@section('content')
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>PO No</th>
                <th>Company</th>
                <th>Supplier</th>
                <th>Description</th>
                <th>Contact</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Status</th>
                <th width="160">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pos as $index => $po)
                <tr>
                    <td>{{ $pos->firstItem() + $index }}</td>
                    <td>{{ $po->po_number }}</td>
                    <td>{{ $po->purchase_company }}</td>
                    <td>{{ $po->supplier_name  }}</td>
                    <td>{{ $po->description  }}</td>
                    <td>{{ $po->contact_no  }}</td>
                    <td>{{ $po->quantity  }}</td>
                    <td>{{ $po->rate  }}</td>
                    <td>{{ $po->status  }}</td>
                    <td>
                        <a href="{{ route('po.show', $po->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="#" class="btn btn-sm btn-warning">Edit</a>
                        <a href="#" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No Purchase Orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Links -->
<div>
    {{ $pos->links() }}
</div>
@endsection
