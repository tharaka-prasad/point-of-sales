@extends('layouts.master')

@section('title')
    <h3 class="mb-0">{{ $menu ?? 'Kitchen Issue' }}</h3>
@endsection

@section('breadcumb')
    @parent
    <li class="breadcrumb-item active" aria-current="page">{{ $menu ?? 'Kitchen Issue' }}</li>
@endsection

@section('content')
    <div class="app-content">

        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">

                    <div class="card mb-4">

                        <div class="card-header">
                            <button class="btn btn-primary xs" onclick="addKitchen('{{ route('kitchen.store') }}')">
                                <i class="fas fa-plus"></i> Add
                            </button>

                            <button type="button" class="btn btn-primary"
                                onclick="addKitchen('{{ route('kitchen.store') }}')">
                                <i class="fas fa-plus"></i> Add Kitchen Item
                            </button>
                        </div>

                        <div class="card-body">

                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                        <th>Issue Date</th>
                                        <th>Issued By</th>
                                        <th width="200">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($kitchen as $k)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $k->item_name }}</td>
                                            <td>{{ $k->qty }}</td>
                                            <td>{{ $k->unit }}</td>
                                            <td>{{ $k->issue_date }}</td>
                                            <td>{{ $k->issued_by }}</td>
                                            <td>
                                                {{-- Future: Edit + Delete buttons --}}
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-danger">No records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            {{-- Pagination --}}
                            <div class="d-flex justify-content-center">
                                {{-- {{ $kitchen->links() }} --}}
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        // add Kitchen Modal
        function addKitchen(url) {
            const $modal = $("#kitchenModal");
            const $form = $modal.find("form");

            if ($form.length === 0) {
                console.error("⚠️ Kitchen form not found!");
                return;
            }

            // Reset form and set action
            $form[0].reset();
            $form.attr("action", url);
            $form.find("[name=_method]").val("POST");

            // Set modal title
            $modal.find(".modal-title").text("Add Kitchen Item");

            // Show modal
            $modal.modal("show");
        }


        // Function: Delete Member
        window.deleteMember = function(url) {
            if (confirm("Are you sure delete this Member?")) {
                $.post(url, {
                        "_token": $("[name=csrf-token]").attr("content"),
                        "_method": "DELETE"
                    })
                    .done((response) => {
                        member_table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert("Failed to delete data!");
                    });
            }
        }

        // ✅ Sidebar animation
        $(function() {
            $("body").toggleClass("sidebar-collapse");
        });
    </script>
@endpush
