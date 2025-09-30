@extends('layouts.master')

@section('title')
    <h3 class="mb-0">{{ $menu ?? 'Purchase Order(PO)' }}</h3>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Purchase Order(PO)</h4>
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
                            <th>Qunatity</th>
                            <th>Rate</th>
                            <th>Status</th>
                            <th width="160">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ + 1 }}</td> $index + 1
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <th></th>
                        </tr>
                    </tbody>

                    </table>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center">
                        {{-- {{ $grns->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
    @endsection
