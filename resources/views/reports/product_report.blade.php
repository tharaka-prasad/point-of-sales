@extends('layouts.master')

@section('title')
    <h3 class="mb-0">List {{ $menu }}</h3>
@endsection

@section('breadcumb')
    @parent
    <li class="breadcrumb-item active">{{ $menu }}</li>
@endsection

@section('content')
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">

                    <div class="card-header">
                        <button class="btn btn-primary xs" onclick="addProduct('{{ route('product.store') }}')">
                            <i class="fas fa-plus"></i> Add Product
                        </button>
                        <a href="{{ route('reports.product_exportPdf') }}" target="_blank" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Export to PDF
                        </a>
                    </div>

                    <div class="card-body">
                        <table id="product_table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Code</th>
                                    <th>Cost</th>
                                    <th>Price</th>
                                    <th>Curent Stock</th>
                                    <th>Created Date</th>
                                    <th><i class="fas fa-cog"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@includeIf('product.form')
@endsection

@push('scripts')
<script>
$(function() {
    let product_table = $("#product_table").DataTable({
        responsive: true,
        serverSide: true,
        processing: true,
        ajax: "{{ route('product.data') }}",
        columns: [
            {data: "DT_RowIndex", searchable: false, sortable: false},
            {data: "name"},
            {data: "category", defaultContent: "-"},
            {data: "code"},
            {data: "price"},
            {data: "sell_price"},
            {data: "stock"},
            {data: "created_at", render: function(data){
                let date = new Date(data);
                return date.getFullYear() + "-" +
                       String(date.getMonth()+1).padStart(2,'0') + "-" +
                       String(date.getDate()).padStart(2,'0');
            }},
            {data: "action", searchable: false, sortable: false}
        ]
    });

    // Add/Edit form submission via AJAX
    $("#modalForm form").on("submit", function(e){
        e.preventDefault();
        let form = $(this);
        let url = form.attr("action");
        let method = $("#modalForm [name=_method]").val() || 'POST';
        $.ajax({
            url: url,
            method: method === 'POST' ? 'POST' : 'PUT',
            data: form.serialize(),
            success: function(res){
                $("#modalForm").modal("hide");
                product_table.ajax.reload();
            },
            error: function(err){
                alert("Failed to save data!");
            }
        });
    });
});

// Open Add Product modal
function addProduct(url) {
    $("#modalForm").modal("show");
    $("#modalForm .modal-title").text("Add Product");

    let form = $("#modalForm form")[0];
    form.reset();

    $("#modalForm form").attr("action", url);
    $("#modalForm [name=_method]").val("POST");
}

// Open Edit Product modal
function editProduct(url) {
    $.get(url.replace('/update',''), function(product){
        $('#product_name').val(product.product_name);
        $('#product_code').val(product.product_code);
        $('#cost').val(product.cost);
        $('#price').val(product.price);
        $('#stock').val(product.stock);
        $('#category_id').val(product.category_id);

        $("#modalForm form").attr("action", url);
        $("#modalForm [name=_method]").val("PUT");
        $("#modalForm .modal-title").text("Edit Product");

        $("#modalForm").modal("show");
    });
}

// Delete Product
function deleteProduct(url) {
    if(confirm("Are you sure delete this product?")) {
        $.post(url, {
            "_token": $("[name=csrf-token]").attr("content"),
            "_method": "DELETE"
        })
        .done(() => product_table.ajax.reload())
        .fail(() => alert("Failed to delete product!"));
    }
}
</script>
@endpush
