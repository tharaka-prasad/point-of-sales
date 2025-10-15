@extends('layouts.master')

@section('title')
    <h3 class="mb-0">List {{ $menu }}</h3>
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
                            <button class="btn btn-primary xs" onclick="addSupplier('{{ route('supplier.store') }}')">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">
                            <table id="supplier_table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Supplier Name</th>
                                        <th>Company Name</th>
                                        <th>Category</th>
                                        <th>Phone</th>
                                        <th>Address</th>
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
    @includeIf('supplier.form')
@endsection

@push('scripts')
    <script>
        let supplier_table;

        $(function() {
            $("body").addClass("sidebar-collapse");

            supplier_table = $("#supplier_table")
                .DataTable({
                    responsive: true,
                    lengthChange: false,
                    autoWidth: false,
                    serverSide: true,
                    processing: true,
                    ajax: {
                        url: "{{ route('supplier.data') }}",
                    },
                    columns: [{
                            data: "DT_RowIndex",
                            searchable: false,
                            sortable: false
                        },
                        {
                            data: "supplier_name"
                        },
                        {
                            data: "company_name"
                        },
                        {
                            data: "category_id"
                        },
                        {
                            data: "phone"
                        },
                        {
                            data: "address"
                        },
                        {
                            data: "action",
                            searchable: false,
                            sortable: false
                        }
                    ]
                });

            $("#modalForm").on("submit", function(e) {
                if (!e.preventDefault()) {
                    $.post($("#modalForm form").attr("action"), $("#modalForm form").serialize())
                        .done((response) => {
                            // Success
                            $("#modalForm").modal("hide");

                            supplier_table.ajax.reload();
                        })
                        .fail((errors) => {
                            // Failed
                            alert("Failed to save data!");

                            return;
                        });
                }
            });
        });

        // Function: Add Supplier
        function addSupplier(url) {
            $("#modalForm").modal("show"); // Show modal
            $("#modalForm .modal-title").text("Add Supplier"); //add supplier

            $("#modalForm form")[0].reset();
            $("#modalForm form").attr("action", url);
            $("#modalForm [name=_method]").val("POST"); // this is an create data
        }

        // Function: Edit Supplier
        function editSupplier(url) {
            $("#modalForm").modal("show"); // Show modal
            $("#modalForm .modal-title").text("Edit Supplier"); //edit supplier

            const form = $("#modalForm form")[0]; // Reset form and set action + method
            form.reset(); //reset form
            $(form).attr("action", url);
            $("#modalForm [name=_method]").val("PUT"); //this is an update

            // Fetch supplier data
            $.get(url)
                .done(response => {
                    console.log("Supplier Data:", response); // Debug

                    // Populate fields
                    $("#modalForm [name=supplier_name]").val(response.name || response.supplier_name || '');
                    $("#modalForm [name=company_name]").val(response.company || response.company_name || '');
                    $("#modalForm [name=category_id]").val(response.category || response.category_id || '');
                    $("#modalForm [name=phone]").val(response.phone || '');
                    $("#modalForm [name=address]").val(response.address || '');

                    // Populate category dropdown and select current category
                    loadCategories(response.category_id || null);
                })
                .fail(error => {
                    console.error("Error fetching supplier data:", error);
                    alert("Failed to load supplier data!");
                });
        }

        // Function: Delete Supplier
        function deleteSupplier(url) {
            if (confirm("Are you sure delete this supplier?")) {
                // Delete Data
                $.post(url, {
                        "_token": $("[name=csrf-token]").attr("content"),
                        "_method": "DELETE"
                    })
                    .done(response => {
                        // Success
                        supplier_table.ajax.reload();
                    })
                    .fail(errors => {
                        // Failed
                        alert("Failed to delete data!");

                        return;
                    });
            }
        }

        function editProduct(url) {
            $("#modalForm").modal("show");
            $("#modalForm .modal-title").text("Edit Product");

            const form = $("#modalForm form")[0];
            form.reset();
            $("#modalForm [name=_method]").val("PUT");
            $(form).attr("action", url);

            // Get product data
            $.get(url)
                .done(response => {
                    $("#modalForm [name=name]").val(response.name || '');
                    $("#modalForm [name=price]").val(response.price || '');
                    $("#modalForm [name=category_id]").val(response.category_id || '');

                    // Load suppliers dynamically and pre-select
                    loadSuppliers(response.supplier_id || null);
                })
                .fail(() => alert("Failed to load product data!"));
        }

        function editSupplier(url) {
            $.get(url, function(data) {
                // other input fields
                $('#supplier_id').val(data.id);
                $('#name').val(data.name);
                $('#phone').val(data.phone);
                $('#email').val(data.email);

                // ✅ Category fix
                if (data.category_id) {
                    $('#category_id').val(data.category_id).trigger('change');
                } else if (data.category && data.category.id) {
                    $('#category_id').val(data.category.id).trigger('change');
                }
            });
        }
    </script>
@endpush
