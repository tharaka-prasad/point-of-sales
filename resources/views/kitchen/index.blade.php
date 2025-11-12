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
                                <i class="fas fa-plus"></i> Add Kitchen Issue
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

        // ✅ Open Kitchen Modal
        function addKitchen(url) {
            $("#modalForm").modal("show");
            $("#modalForm .modal-title").text("Add Kitchen Issue");

            let form = $("#modalForm form")[0];
            form.reset();

            $("#modalForm form").attr("action", url);
            $("#modalForm [name=_method]").val("POST");
        }

        // Function: Add Member
            window.addMember = function(url) {
                $("#modalForm").modal("show");
                $("#modalForm .modal-title").text("Add Member");

                $("#modalForm form")[0].reset();
                $("#modalForm form").attr("action", url);
                $("#modalForm [name=_method]").val("POST");
            }

        // Function: Edit Member
            window.editMember = function(url) {
                $("#modalForm").modal("show");
                $("#modalForm .modal-title").text("Edit Member");

                $("#modalForm form")[0].reset();
                $("#modalForm form").attr("action", url);
                $("#modalForm [name=_method]").val("PUT");
            }

        // Function: Delete
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
