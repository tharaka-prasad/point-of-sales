@extends('layouts.master')

@section('title')
    <h3 class="mb-0">{{ $menu ?? 'Goods Received Notes (GRN)' }}</h3>
@endsection

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Goods Received Notes (GRN)</h4>
            <a href="{{ route('grn.create') }}" class="btn btn-primary">+ New GRN</a>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>GRN No</th>
                        <th>Date</th>
                        <th>Supplier</th>
                        <th>PO No</th>
                        <th>Invoice No</th>
                        <th>Department</th>
                        <th>Prepared By</th>
                        <th>Total</th>
                        <th width="160">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grns as $index => $grn)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $grn->grn_no }}</td>
                            <td>{{ \Carbon\Carbon::parse($grn->grn_date)->format('Y-m-d') }}</td>
                            <td>{{ $grn->supplier }}</td>
                            <td>{{ $grn->po_no }}</td>
                            <td>{{ $grn->invoice_no }}</td>
                            <td>{{ $grn->department }}</td>
                            <td>{{ $grn->prepared_by }}</td>
                            <td>{{ number_format($grn->grand_total, 2) }}</td>
                            <td>
                                <a href="{{ route('grn.show', $grn->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('grn.edit', $grn->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <a href="{{ route('grn.download', $grn->id) }}" class="btn btn-sm btn-success">PDF</a>
                                <form action="{{ route('grn.destroy', $grn->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this GRN?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">No GRNs found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {{ $grns->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
