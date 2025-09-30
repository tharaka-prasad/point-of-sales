@extends('layouts.master')

@section('title')
    <h3 class="mb-0">Purchase Order (PO) {{ $menu }}</h3>
@endsection

@section('breadcumb')
    @parent
    <li class="breadcrumb-item active" aria-current="page">{{ $menu }}</li>
@endsection

@section('content')
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <button class="btn btn-primary xs" onclick="addPurchaseOrder('{{ route('po.create') }}')">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">
                            <table id="po_table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>PO Number</th>
                                        <th>Company</th>
                                        <th>Supplier</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Rate</th>
                                        <th>Status</th>
                                        <th>
                                            <i class="fas fa-cog"></i>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- ./card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row (main row) -->
        </div>
        <!--end::Container-->
    </div>

    @includeIf('po.form')
@endsection

@push('scripts')
    <script>
        let po_table;

        $(function() {
            $("body").addClass("sidebar-collapse");

            po_table = $("#po_table").DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('po.data') }}",
                },
                columns: [
                    { data: "DT_RowIndex", searchable: false, sortable: false },
                    { data: "po_number" },
                    { data: "company_name" },
                    { data: "supplier_name" },
                    { data: "description" },
                    { data: "quantity" },
                    { data: "rate" },
                    { data: "status" },
                    { data: "action", searchable: false, sortable: false }
                ]
            });

            $("#modalForm").on("submit", function(e) {
                e.preventDefault();
                $.post($("#modalForm form").attr("action"), $("#modalForm form").serialize())
                    .done((response) => {
                        $("#modalForm").modal("hide");
                        po_table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert("Failed to save data!");
                    });
            });
        });

        // Function: Add PO
        function addPurchaseOrder(url) {
            $("#modalForm").modal("show");
            $("#modalForm .modal-title").text("Add Purchase Order");

            $("#modalForm form")[0].reset();
            $("#modalForm form").attr("action", url);
            $("#modalForm [name=_method]").val("POST");
        }

        // Function: Edit PO
        function editPurchaseOrder(url) {
            $("#modalForm").modal("show");
            $("#modalForm .modal-title").text("Edit Purchase Order");

            $("#modalForm form")[0].reset();
            $("#modalForm form").attr("action", url);
            $("#modalForm [name=_method]").val("PUT");

            $.get(url)
                .done(response => {
                    $("#modalForm [name=po_number]").val(response.po_number);
                    $("#modalForm [name=company_name]").val(response.company_name);
                    $("#modalForm [name=supplier_name]").val(response.supplier_name);
                    $("#modalForm [name=description]").val(response.description);
                    $("#modalForm [name=quantity]").val(response.quantity);
                    $("#modalForm [name=rate]").val(response.rate);
                    $("#modalForm [name=status]").val(response.status);
                })
                .fail(() => {
                    alert("Failed to display data!");
                });
        }

        // Function: Delete PO
        function deletePurchaseOrder(url) {
            if (confirm("Are you sure delete this purchase order?")) {
                $.post(url, {
                        "_token": $("[name=csrf-token]").attr("content"),
                        "_method": "DELETE"
                    })
                    .done(() => {
                        po_table.ajax.reload();
                    })
                    .fail(() => {
                        alert("Failed to delete data!");
                    });
            }
        }
    </script>
@endpush
