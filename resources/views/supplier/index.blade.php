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
                            data: "name"
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

    // Handle Add/Edit form submission via AJAX
    $("#modalForm form").on("submit", function(e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr("action");
        let method = $("#modalForm [name=_method]").val() || 'POST';

        $.ajax({
            url: url,
            method: method === 'POST' ? 'POST' : 'PUT',
            data: form.serialize(),
            success: function(res) {
                $("#modalForm").modal("hide");
                supplier_table.ajax.reload();
            },
            error: function(err) {
                console.error(err);
                alert("Failed to save data!");
            }
        });
    });
});

// Function to load categories dynamically and set selected
function loadCategories(selectedId = null) {
    $.get('/category/list', function(categories) {
        let options = '<option value="">-- Select Category --</option>';
        $.each(categories, function(i, cat) {
            let selected = cat.id == selectedId ? 'selected' : '';
            options += `<option value="${cat.id}" ${selected}>${cat.name}</option>`;
        });
        $('#category_id').html(options);
    });
}

// Open Add Supplier modal
function addSupplier(url) {
    $("#modalForm").modal("show");
    $("#modalForm .modal-title").text("Add Supplier");

    // Clear form
    let form = $("#modalForm form")[0];
    form.reset();

    // Set form action/method
    $("#modalForm form").attr("action", url);
    $("#modalForm [name=_method]").val("POST");

    // Load categories (none selected)
    loadCategories();
}

// Open Edit Supplier modal
function editSupplier(url) {
    $.get(url.replace('/update',''), function(supplier) {
        // Fill form fields
        $('#supplier_name').val(supplier.supplier_name);
        $('#company_name').val(supplier.company_name);
        $('#phone').val(supplier.phone);
        $('#address').val(supplier.address);

            $("#modalForm form")[0].reset();
            $("#modalForm form").attr("action", url);
            $("#modalForm [name=_method]").val("PUT");

            // Get Data
            $.get(url)
                .done(response => {
                    // Success
                    suplier_name
                    $("#modalForm [name=supplier_name]").val(response.name);
                    $("#modalForm [name=company_name]").val(response.name);
                    $("#modalForm [name=name]").val(response.name);
                    $("#modalForm [name=phone]").val(response.phone);
                    $("#modalForm [name=address]").val(response.address);
                })
                .fail(errors => {
                    alert("Failed to display data!");
                });
        }

// Delete Supplier
function deleteSupplier(url) {
    if (confirm("Are you sure delete this supplier?")) {
        $.post(url, {
            "_token": $("[name=csrf-token]").attr("content"),
            "_method": "DELETE"
        })
        .done(() => supplier_table.ajax.reload())
        .fail(() => alert("Failed to delete supplier!"));
    }
}
</script>
@endpush

