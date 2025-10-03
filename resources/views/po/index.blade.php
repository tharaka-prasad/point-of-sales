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
                            <th>Quantity</th>
                            <th>Rate(Rs)</th>
                            <th>Status</th>
                            <th>Total(Rs)</th>
                            <th width="160">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pos as $index => $po)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $po->po_number }}</td>
                                <td>{{ $po->purchase_company }}</td>
                                <td>{{ $po->supplier_id }}</td>
                                <td>{{ $po->description }}</td>
                                <td>{{ $po->contact_no }}</td>
                                <td>{{ $po->quantity }}</td>
                                <td>{{ $po->rate }}</td>
                                <td>{{ $po->status }}</td>
                                <td>{{ number_format($po->grand_total, 2) }}</td>
                                <td></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">No pos found</td>
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
