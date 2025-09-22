@extends('layouts.master')

@section('title')
    <h1 class="m-0">{{ $menu }}</h1>
@endsection

@section('breadcumb')
    @parent
    <li class="breadcrumb-item active">{{ $menu }}</li>
@endsection

@section('content')
    <div class="container-fluid">

     <!-- 1_ROW-->
        <div class="row">
            <div class="col-lg-3 col-6">
                <!--BOX_1-->
                <div class="small-box bg-warning shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h4>Total Catogeries</h4>
                        <h3>{{ $total_category }}</h3>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <a href="{{ route('category.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!--BOX_2-->

                <div class="small-box bg-warning shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h4>Total Suppliers</h4>
                        <h3>{{ $total_supplier }}</h3>
                    </div>
                    <div class="icon">
                        <i class="fas fa-reply"></i>
                    </div>
                    <a href="{{ route('supplier.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!--BOX_3-->
                <div class="small-box bg-warning shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h4>Total Customers</h4>
                        <h3>{{ $total_member }}</h3>
                    </div>
                    <div class="icon">
                        <i class="fas fa-reply"></i>
                    </div>
                    <a href="{{ route('member.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!--BOX_4-->
                <div class="small-box bg-warning shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h4>Total Hold Orders</h4>
                        <h3>{{ $total_product }}</h3>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <a href="{{ route('product.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

     <!-- 2_ROW-->
        <div class="row">
            <div class="col-lg-3 col-6">
                <!--BOX_5-->
                <div class="small-box bg-warning shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h3>{{ $total_category }}</h3>
                        <h4>Today Total Sales</h4>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <a href="{{ route('category.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!--BOX_6-->
                <div class="small-box bg-warning shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h3>{{ $total_product }}</h3>
                        <h4>Today Total Returns</h4>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <a href="{{ route('product.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!--BOX_7-->
                <div class="small-box bg-warning shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h3>{{ $total_member }}</h3>
                        <h4>Today Total Purchases</h4>
                    </div>
                    <div class="icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <a href="{{ route('member.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!--BOX_8-->
                <div class="small-box bg-warning shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h3>{{ $total_supplier }}</h3>
                        <h4>Today Total Expense</h4>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <a href="{{ route('supplier.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

     <!--ROW_3-->
        <div class="row">

            {{-- CHART 1 iNCOME RECAP CHART --}}
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Income Recap Report ( {{ lkr_money_format(date('Y-m-01'), false) }} -
                            {{ lkr_date(date('Y-m-d'), false) }}
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="chart">
                                    <!-- Income Recap Chart Canvas -->
                                    <canvas id="incomeRecapChart" height="180" style="height: 180px"></canvas>
                                </div>
                                <!-- /.chart-responsive -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- ./card-body -->
                </div>
                <!-- /.card -->
            </div>

            {{-- CHART 2 new pie chart Top selling products --}}
            <div class="col">
                {{-- CHART 2 new pie chart Top selling products --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Income Distribution</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="incomePieChart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>

        </div>

        {{-- ROW 2 --}}
        <div class="row">
            <div class="col">
                {{-- CHART-3 new pie chart Top selling products-2 --}}

                <div class="card" style="border-radius: 15px; box-shadow: 0 6px 12px rgba(0,0,0,0.2);">
                    <div class="card-header" style="background: #f39c12; border-radius: 15px 15px 0 0;">
                        <h3 class="card-title" style="color: #fff; font-weight: bold;">
                            <i class="fas fa-chart-pie"></i> Top Selling Products
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="topProductPieChart"
                            style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;">
                        </canvas>
                    </div>
                </div>
            </div>

            <div class="col">
                {{-- CHART 4 new pie chart Top selling products-2 --}}
                <div class="card" style="border-radius: 15px; box-shadow: 0 6px 12px rgba(0,0,0,0.2);">
                    <div class="card-header" style="background: #f39c12; border-radius: 15px 15px 0 0;">
                        <h3 class="card-title" style="color: #fff; font-weight: bold;">
                            <i class="fas fa-chart-pie"></i> Top Selling Products
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="topProductPieChart"
                            style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;">
                        </canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 3 --}}
        <div class="row">

        </div>
        {{-- ROW 4 --}}
        <div class="row">

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            'use strict'

            $("body").addClass("sidebar-collapse");

            //-----------------------
            // - INCOME RECAP CHART -
            //-----------------------

            var incomeRecapChartCanvas = $('#incomeRecapChart').get(0).getContext('2d');

            var incomeRecapChartData = {
                labels: {{ json_encode($data_date) }},
                datasets: [{
                    label: 'Income',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: false,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: {{ json_encode($data_income) }}
                }]
            };

            var incomeRecapChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: true
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: true
                        }
                    }]
                }
            };

            var incomeRecapChart = new Chart(incomeRecapChartCanvas, {
                type: 'line',
                data: incomeRecapChartData,
                options: incomeRecapChartOptions
            });

            //---------------------------
            // - END INCOME RECAP CHART -
            //---------------------------
        });
    </script>
@endpush
