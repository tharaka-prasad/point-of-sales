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
                <div class="small-box bg-gradient-green shadow-sm" style="border-radius:10px;overflow:hidden;">
                    {{-- <div class="small-box bg-warning shadow-sm" style="border-radius:10px;overflow:hidden;"> --}}
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

                <div class="small-box bg-gradient-blue shadow-sm" style="border-radius:10px;overflow:hidden;">

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
                <div class="small-box bg-gradient-red shadow-sm" style="border-radius:10px;overflow:hidden;">
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
                <div class="small-box bg-gradient-teal shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h4>Total Products</h4>
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
                <div class="small-box bg-gradient-purple shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h3>LKR {{ $today_total_sales }}</h3>
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
                <div class="small-box bg-gradient-orange shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h3>LKR {{ $today_total_return }}</h3>
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
                        <h3>LKR {{ $today_total_purchases }}</h3>
                        <h4>Today Total Purchases</h4>
                    </div>
                    <div class="icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <a href="{{ route('grn.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!--BOX_8-->
                <div class="small-box bg-gradient-purple shadow-sm" style="border-radius:10px;overflow:hidden;">
                    <div class="inner">
                        <h3>LKR {{ $total_expense }}</h3>
                        <h4>Today Total Expense</h4>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <a href="{{ route('expense.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        {{-- Dashboard Charts --}}
        <div class="row">
            {{-- Income Recap Chart --}}
            <div class="col-lg-6">
                <div class="card shadow-sm" style="border-radius: 10px; overflow: hidden;">
                    <div class="card-body">
                        <h4>Daily Income (Blade Data)</h4>
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="incomeRecapChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sales / Expenses / Income Chart --}}
            <div class="col-lg-6">
                <div class="card shadow-sm" style="border-radius: 10px; overflow: hidden;">
                    <div class="card-body">
                        <h4>Sales, Expenses & Net Income (Last 7 Days)</h4>
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="incomeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daily Sales by Cashier --}}
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card shadow-sm" style="border-radius:10px; overflow:hidden;">
                    <div class="card-header">
                        <h5>Daily Sales by Cashier ({{ date('Y-m-d') }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="salesByCashierChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
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

        {{-- ROW 5 --}}
        <div class="row">

        </div>
        {{-- ROW 6 --}}
        <div class="row">

        </div>

    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    //---------------------------
    // 1️⃣ INCOME RECAP CHART
    //---------------------------
    var incomeRecapChartCanvas = document.getElementById('incomeRecapChart').getContext('2d');

    var incomeRecapChartData = {
        labels: {!! json_encode($data_date) !!},
        datasets: [{
            label: 'Income',
            data: {!! json_encode($data_income) !!},
            borderColor: 'rgba(60,141,188,0.8)',
            backgroundColor: 'rgba(60,141,188,0.2)',
            fill: true,
            tension: 0.3
        }]
    };

    new Chart(incomeRecapChartCanvas, {
        type: 'line',
        data: incomeRecapChartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: true } },
            scales: {
                x: { grid: { display: true } },
                y: { beginAtZero: true, grid: { display: true } }
            }
        }
    });

    //---------------------------
    // 2️⃣ SALES / EXPENSES / INCOME CHART
    //---------------------------
    fetch("{{ route('reports.income-chart-data') }}")
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('incomeChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Sales (LKR)',
                            data: data.sales,
                            borderColor: '#00bcd4',
                            backgroundColor: 'rgba(0, 188, 212, 0.1)',
                            fill: true,
                            tension: 0.3
                        },
                        {
                            label: 'Expenses (LKR)',
                            data: data.expenses,
                            borderColor: '#f44336',
                            backgroundColor: 'rgba(244, 67, 54, 0.1)',
                            fill: true,
                            tension: 0.3
                        },
                        {
                            label: 'Net Income (LKR)',
                            data: data.income,
                            borderColor: '#4caf50',
                            backgroundColor: 'rgba(76, 175, 80, 0.1)',
                            fill: true,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: true } },
                    scales: { x: {}, y: { beginAtZero: true } }
                }
            });
        });

    //---------------------------
    // 3️⃣ DAILY SALES BY CASHIER CHART
    //---------------------------
    var salesByCashierLabels = {!! json_encode($dailySalesByCashier->pluck('id')) !!};
    var salesByCashierData = {!! json_encode($dailySalesByCashier->pluck('total_price')) !!};

    var salesByCashierCtx = document.getElementById('salesByCashierChart').getContext('2d');

    new Chart(salesByCashierCtx, {
        type: 'bar',
        data: {
            labels: salesByCashierLabels,
            datasets: [{
                label: 'Sales (LKR)',
                data: salesByCashierData,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: true } },
            scales: { y: { beginAtZero: true }, x: { grid: { display: true } } }
        }
    });

});
</script>
@endpush
