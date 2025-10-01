@extends('layouts.master')

@section('title')
    <h3 class="mb-0">{{ $menu ?? 'Goods Received Notes (GRN)' }}</h3>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Goods Received Notes (GRN)</h4>
                <a href="{{ route('grn.create') }}" class="btn btn-primary ">+ New GRN</a>
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
                        <th>Prepared By</th>
                        <th>Total(Rs)</th>
                        <th width="160">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grns as $index => $grn)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $grn->grn_no }}</td>
                            <td>{{ \Carbon\Carbon::parse($grn->date)->format('Y-m-d') }}</td>
                            <td>{{ $grn->supplier_name }}</td>
                            <td>{{ $grn->po_no }}</td>
                            <td>{{ $grn->invoice_no }}</td>
                            <td>{{ $grn->creator_name }}</td>
                            <td>{{ number_format($grn->grand_total, 2) }}</td>
                            <td>
                                <a href="{{ route('grn.show', $grn->id) }}" class="btn btn-sm btn-info">View</a>
                                {{-- Optional actions: Edit, PDF, Delete --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No GRNs found</td>
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
