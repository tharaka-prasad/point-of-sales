<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard.index') }}" class="brand-link">
        @php
            $logoPath = $setting->path_logo ?? null;
        @endphp
        <img
            src="{{ $logoPath && file_exists(public_path($logoPath)) ? asset($logoPath) : asset('admin/images/logo-2025-09-18074601.jpg') }}"
            alt="Point Of Sale Logo"
            class="brand-image img-circle elevation-3"
            style="opacity: 0.8"
        >
        <span class="brand-text font-weight-light">{{ $setting->company_name ?? config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="{{ route('user.profile') }}" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @if(Auth::user()->current_team_id == 1)
                    <!-- MASTER Section -->
                    <li class="nav-header">MASTER</li>
                    <li class="nav-item">
                        <a href="{{ route('category.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Category</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('product.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-cubes"></i>
                            <p>Product</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('member.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-id-card"></i>
                            <p>Member</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('supplier.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-box"></i>
                            <p>Supplier</p>
                        </a>
                    </li>

                    <!-- TRANSACTION Section -->
                    <li class="nav-header">TRANSACTION</li>
                    <li class="nav-item">
                        <a href="{{ route('expense.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-money-bill-alt"></i>
                            <p>Expense</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('purchase.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>Purchase</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('sale.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Sale</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('transaction.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-cart-arrow-down"></i>
                            <p>Active Transaction</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('transaction.new') }}" class="nav-link">
                            <i class="nav-icon fas fa-cart-plus"></i>
                            <p>New Transaction</p>
                        </a>
                    </li>

                    <!-- CASHIER Section -->
                    <li class="nav-header">CASHIER</li>
                    <li class="nav-item">
                        <a href="{{ route('cashier.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>Cashier</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('cashierShifts.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>Cashier Shift</p>
                        </a>
                    </li>

                    <!-- REPORT Section -->
                    <li class="nav-header">REPORT</li>
                    <li class="nav-item">
                        <a href="{{ route('report.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Report</p>
                        </a>
                    </li>

                    <!-- SYSTEM Section -->
                    <li class="nav-header">SYSTEM</li>
                    <li class="nav-item">
                        <a href="{{ route('setting.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Settings</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link"
                           onclick="document.getElementById('logout-form').submit()">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                @else
                    <li class="nav-header">TRANSACTION</li>
                    <li class="nav-item">
                        <a href="{{ route('transaction.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-cart-arrow-down"></i>
                            <p>Active Transaction</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('transaction.new') }}" class="nav-link">
                            <i class="nav-icon fas fa-cart-plus"></i>
                            <p>New Transaction</p>
                        </a>
                    </li>
                    <li class="nav-header">SYSTEM</li>
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link"
                           onclick="document.getElementById('logout-form').submit()">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

{{-- Form Logout --}}
<form action="{{ route('logout') }}" method="post" class="d-none" id="logout-form">
    @csrf
</form>
