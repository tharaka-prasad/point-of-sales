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
        <div class="row p-2">
            <div class="col"
                style="
                        display: inline-block;
                        border-radius: 15px;
                        background: linear-gradient(145deg, #fff5f5, #fff7e6);
                        color: #d90404;
                        box-shadow: 0 6px 12px rgba(0,0,0,0.2);
                        border: 1px solid #ffba08;
                        text-align: center;
                        padding: 10px 20px;
                        font-family: 'Courier New', monospace;
                        min-width: 160px;
                    ">
                <h4 style="font-weight: bold; font-size: 1.5rem; margin: 0; color: #d90404;">
                    <span id="digitalTime">00:00</span>
                    <span id="ampm" style="font-weight:bold;">AM</span>
                </h4>
            </div>


        </div>

        <div class="row">

            <div class="col-lg-3 col-6">
                <!-- small box 1-->
                <div class="small-box bg-warning shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h3>{{ $total_category }}</h3>
                        <h4>Sales</h4>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <a href="{{ route('category.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!-- small box 2-->
                <div class="small-box bg-warning shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h3>{{ $total_product }}</h3>

                        <h4>Purchases</h4>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <a href="{{ route('product.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!-- small box 3-->
                <div class="small-box bg-warning shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h3>{{ $total_member }}</h3>

                        <h4>Salse Return</h4>
                    </div>
                    <div class="icon">
                        <i class="fas fa-reply"></i>
                    </div>
                    <a href="{{ route('member.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!-- small box 4-->
                <div class="small-box bg-warning shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h3>{{ $total_supplier }}</h3>

                        <h4>Purchases Returns</h4>
                    </div>
                    <div class="icon">
                        <i class="fas fa-reply"></i>
                    </div>
                    <a href="{{ route('supplier.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box 5-->
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
                <!-- small box 6-->
                <div class="small-box bg-warning shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h3>{{ $total_product }}</h3>

                        <h4>Today Total</h4>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <a href="{{ route('product.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!-- small box 7-->
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
                <!-- small box 8-->
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

        {{-- ROW 1 --}}
        <div class="row">
            {{-- CHART 1 iNCOME RECAP CHART --}}
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Income Recap Report ( {{ indonesia_date(date('Y-m-01'), false) }} -
                            {{ indonesia_date(date('Y-m-d'), false) }} )</h5>
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
        function updateDigitalClock() {
            var now = new Date();

            var hours = now.getHours();
            var minutes = String(now.getMinutes()).padStart(2, '0');
            var ampm = hours >= 12 ? 'PM' : 'AM';

            // Convert 24h → 12h format
            hours = hours % 12;
            hours = hours ? hours : 12;
            hours = String(hours).padStart(2, '0');

            // Update time
            document.getElementById('digitalTime').innerText = hours + ':' + minutes;

            // Update AM/PM with different colors
            var ampmElement = document.getElementById('ampm');
            ampmElement.innerText = ampm;
            if (ampm === 'AM') {
                ampmElement.style.color = '#d90404'; // red
            } else {
                ampmElement.style.color = '#ffba08'; // yellow
            }
        }

        // Run every second
        setInterval(updateDigitalClock, 1000);
        updateDigitalClock();
        //

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

        // PIE CHART
        var pieChartCanvas = $('#incomePieChart').get(0).getContext('2d');

        var pieData = {
            labels: {{ json_encode($data_date) }}, // e.g., ['Jan', 'Feb', 'Mar']
            datasets: [{
                data: {{ json_encode($data_income) }}, // same as your income data
                backgroundColor: [
                    '#f56954', // red
                    '#00a65a', // green
                    '#f39c12', // yellow
                    '#00c0ef', // aqua
                    '#3c8dbc', // blue
                    '#d2d6de' // gray
                ],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        };

        var pieOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                position: 'right'
            }
        };

        var pieChart = new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData,
            options: pieOptions
        });
    </script>
@endpush

{{--
        // TOP PRODUCT SELLING PIE CHART
var productPieCanvas = $('#topProductPieChart').get(0).getContext('2d');

var productPieData = {
    labels: {!! json_encode($topProducts->pluck('name')) !!}, // product names
    datasets: [{
        data: {!! json_encode($topProducts->pluck('sales')) !!}, // product sales
        backgroundColor: [
            '#f56954', // red
            '#00a65a', // green
            '#f39c12', // yellow
            '#00c0ef', // aqua
            '#3c8dbc', // blue
            '#d2d6de'  // gray
        ],
        borderColor: '#fff',
        borderWidth: 2
    }]
};

var productPieOptions = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
        position: 'right'
    },
    tooltips: {
        callbacks: {
            label: function(tooltipItem, data) {
                var dataset = data.datasets[tooltipItem.datasetIndex];
                var total = dataset.data.reduce((a, b) => a + b, 0);
                var currentValue = dataset.data[tooltipItem.index];
                var percentage = ((currentValue / total) * 100).toFixed(1);
                return data.labels[tooltipItem.index] + ': ' + currentValue + ' (' + percentage + '%)';
            }
        }
    }
};

new Chart(productPieCanvas, {
    type: 'pie',
    data: productPieData,
    options: productPieOptions
}); --}}
