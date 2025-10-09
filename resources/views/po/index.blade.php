@extends('layouts.master')

@section('title')
    <h3 class="mb-0">{{ $menu ?? 'Purchase Order (PO)' }}</h3>
@endsection

@section('breadcumb')
    @parent
    <li class="breadcrumb-item active" aria-current="page">{{ $menu }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Purchase Order (PO)</h4>
                <a href="{{ route('po.create') }}" class="btn btn-primary ">+ New PO</a>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>PO No</th>
                            <th>Company</th>
                            <th>Supplier</th>
                            <th>Description</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Total(Rs)</th>
                            <th width="200">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pos as $index => $po)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $po->po_number }}</td>
                                <td>{{ $po->supplier->company_name  ?? 'N/A'  }}</td>
                                <td>{{ $po->supplier->name ?? 'N/A' }}</td>
                                <td>{{ $po->description ?? 'N/A'  }}</td>
                                <td>{{ $po->supplier->phone ?? 'N/A'  }}</td>
                                <td>{{ $po->status ?? 'N/A'  }}</td>
                                <td>{{ number_format($po->grand_total, 2) }}</td>
                                <td>
                                    <a href="{{ route('po.show', $po->id) }}" class="btn btn-sm btn-info">View</a>
                                    {{-- Optional actions: Edit, PDF, Delete --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No Purchase Orders found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                    {{-- Pagination --}}

                    <div class="d-flex justify-content-center">
                        {{ $pos->links() }}
                    </div>
            </div>
        </div>
    </div>
@endsection
