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

                    <div class="card-header d-flex justify-content-between align-items-left">
                        <h5 class="mb-0">Kitchen Items</h5>
                        <button type="button" class="btn btn-primary"
                            onclick="addKitchen('{{ route('kitchen.store') }}')">
                            <i class="fas fa-plus"></i> Add Kitchen Item
                        </button>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Unit</th>
                                    <th>Issue Date</th>
                                    <th>Meal Type</th>
                                    <th>Issued By</th>
                                    <th width="180">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($kitchen as $k)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $k->product->name ?? '' }}</td>
                                        <td>{{ $k->qty }}</td>
                                        <td>{{ $k->unit }}</td>
                                        <td>{{ $k->issue_date }}</td>
                                        <td>
                                            <span class="badge badge-info text-uppercase">
                                                {{ $k->meal_type }}
                                            </span>
                                        </td>
                                        <td>{{ $k->issued_by }}</td>
                                        <td>

                                            <!-- EDIT BUTTON -->
                                            <button class="btn btn-sm btn-info"
                                                onclick="editKitchen(
                                                    '{{ route('kitchen.update', $k->id) }}',
                                                    '{{ $k->item_name }}',
                                                    '{{ $k->qty }}',
                                                    '{{ $k->unit }}',
                                                    '{{ $k->issue_date }}',
                                                    '{{ $k->meal_type }}',
                                                    '{{ $k->issued_by }}'
                                                )">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- DELETE BUTTON -->
                                            <button class="btn btn-sm btn-danger"
                                                onclick="deleteKitchen('{{ route('kitchen.destroy', $k->id) }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-danger">No records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

{{-- Include Kitchen Modal --}}
@include('kitchen.form')

@push('scripts')
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: "-- Select Item --",
            allowClear: true
        });
    });

    // Open Add Modal
    function addKitchen(url) {
        const $modal = $("#kitchenModal");
        const $form = $modal.find("form");

        $form[0].reset();
        $form.attr("action", url);
        $form.find("[name=_method]").val("POST");

        $("#item_name").val(null).trigger("change");

        $modal.find(".modal-title").text("Add Kitchen Item");
        $modal.modal("show");
    }

    // Edit Kitchen Item
    function editKitchen(url, item, qty, unit, date, meal, issued) {
        const $modal = $("#kitchenModal");
        const $form = $modal.find("form");

        $form.attr("action", url);
        $form.find("[name=_method]").val("PUT");

        $("#item_name").val(item).trigger("change");
        $("#qty").val(qty);
        $("#unit").val(unit);
        $("#issue_date").val(date);
        $("#meal_type").val(meal);
        $("#issued_by").val(issued);

        $modal.find(".modal-title").text("Edit Kitchen Item");
        $modal.modal("show");
    }

    // Delete Kitchen Item
    function deleteKitchen(url) {
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    "_token": $('meta[name="csrf-token"]').attr("content"),
                    "_method": "DELETE"
                },
                success: function() {
                    location.reload();
                },
                error: function() {
                    alert("Failed to delete data!");
                }
            });
        }
    }

    // Sidebar animation
    $(function() {
        $("body").toggleClass("sidebar-collapse");
    });
</script>

<!-- Select2 Height Fix -->
<style>
    .select2-container .select2-selection--single {
        height: 45px !important;
        padding: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px !important;
        font-size: 16px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
    }
</style>

@endpush
