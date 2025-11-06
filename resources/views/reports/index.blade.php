@extends('layouts.master')

@section('title')
    <h3>{{ $menu }}</h3>
    <h3>{{ $test }}</h3>
    <div>
        <img src="" alt="" srcset="">
    </div>
@endsection

@section('breadcumb')
    @parent
    <li class="breadcrumb-item active" aria-current="page">{{ $menu }}</li>
@endsection

@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h4>Generate Report</h4>

                    <form action="{{ route('reports.generate') }}" method="GET" class="row g-3">
                        @csrf

                        <!-- Report Type -->
                        <div class="col-md-3">
                            <label for="report_type" class="form-label">Report Type</label>
                            <select name="report_type" id="report_type" class="form-control" required>
                                <option value="">-- Select Report Type --</option>
                                <option value="Sales">Sales</option>
                                <option value="Inventory">Inventory</option>
                                <option value="expenses">Sales by Cashier</option>
                                <option value="income">Customer Sales Report</option>
                            </select>
                        </div>

                        <!-- Time Duration -->
                        <div class="col-md-3">
                            <label for="duration" class="form-label">Time Duration</label>
                            <select name="duration" id="duration" class="form-control" required>
                                <option value="">-- Select Duration --</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>

                        <!-- Start Date -->
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                        </div>

                        <!-- End Date -->
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" required>
                        </div>

                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-alt"></i> Generate Report
                            </button>
                        </div>
                    </form>

                    <hr>

                    <p>After submitting, the report will be generated based on your selected filters.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Side bar animation --}}
@push('scripts')
    <script>
        $(function() {
            $("body").toggleClass("sidebar-collapse");
        });
    </script>
@endpush
