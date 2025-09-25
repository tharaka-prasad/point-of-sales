@extends('layouts.master')

@section('title')
    <h3 class="mb-0">List Cashier Shifts</h3>
@endsection

@section('breadcumb')
    @parent
    <li class="breadcrumb-item active">Cashier Shifts</li>
@endsection

@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-header">
                    <button class="btn btn-primary" onclick="addShift('{{ route('cashierShifts.store') }}')">
                        <i class="fas fa-plus"></i> Start Shift
                    </button>
                </div>
                <div class="card-body">
                    <table id="shift_table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cashier</th>
                                <th>Start Time</th>
                                <th>Start Balance</th>
                                <th>End Time</th>
                                <th>End Balance</th>
                                <th>Total Amount</th>
                                <th><i class="fas fa-cog"></i></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('cashier_shift.form') {{-- modal --}}
@endsection

@push('scripts')
<script>
let shift_table;

$(function() {
    shift_table = $('#shift_table').DataTable({
        responsive: true,
        serverSide: true,
        processing: true,
        ajax: "{{ route('cashierShifts.data') }}",
        columns: [
            { data: "DT_RowIndex", searchable: false, sortable: false },
            { data: "cashier" },
            { data: "start_time" },
            { data: "start_balance" },
            { data: "end_time" },
            { data: "end_balance" },
            { data: "total_amount" },
            { data: "action", searchable: false, sortable: false }
        ]
    });

    $("#shiftForm").on("submit", function(e) {
        e.preventDefault();
        const form = this;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(new FormData(form))
        })
        .then(res => res.json())
        .then(data => {
            $('#modalForm').modal('hide');
            shift_table.ajax.reload();
        })
        .catch(err => alert('Failed to save data!'));
    });
});

// START SHIFT modal
function addShift(url) {
    const form = document.getElementById("shiftForm");
    form.reset();
    form.action = url;
    form.querySelector('input[name="_method"]').value = "POST";
    document.querySelector("#modalForm .modal-title").innerText = "Start Shift";

    $(".start-fields").show();
    $(".end-fields").hide();

    $(".cashier-select").prop('disabled', false);
    $(".start-balance").prop('readonly', false);
    $(".start-time").prop('readonly', false);

    // Only visible fields are required
    $(".start-balance").prop('required', true);
    $(".cashier-select").prop('required', true);
    $(".end-balance").prop('required', false);

    $(".start-time").val(new Date().toISOString().slice(0,16));

    $("#modalForm").modal("show");
}

// CLOSE SHIFT modal
function closeShift(url, cashierName, startBalance, startTime) {
    const form = document.getElementById("shiftForm");
    form.reset();
    form.action = url;
    form.querySelector('input[name="_method"]').value = "POST";
    document.querySelector("#modalForm .modal-title").innerText = "Close Shift";

    $(".start-fields").hide();
    $(".end-fields").show();

    $(".end-cashier").val(cashierName);
    $(".end-start-balance").val(startBalance);
    $(".end-start-time").val(startTime);
    $(".end-balance").val("").prop('required', true);

    // Only visible fields are required
    $(".start-balance").prop('required', false);
    $(".cashier-select").prop('required', false);

    $("#modalForm").modal("show");
}

// DELETE SHIFT
function deleteShift(url) {
    if (!confirm("Are you sure delete this shift?")) return;
    fetch(url, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    })
    .then(res => res.json())
    .then(data => shift_table.ajax.reload())
    .catch(err => alert('Failed to delete shift!'));
}
</script>
@endpush
