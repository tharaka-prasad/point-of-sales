@extends('layouts.master')

@section('title')
    <h3 class="mb-0">Sales System</h3>
@endsection

@section('breadcumb')
    @parent
    <li class="breadcrumb-item active" aria-current="page">Sales</li>
@endsection

@section('content')
    <div class="app-content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form id="saleForm" method="POST" action="{{ route('cashier.store') }}">
                @csrf
                <!-- Hidden inputs for totals -->
                <input type="hidden" name="total_item" id="totalItemInput" value="0">
                <input type="hidden" name="total_price" id="totalPriceInput" value="0">

                <div class="row">
                    <!-- Left Panel -->
                    <div class="col-md-8">
                        <div class="card mb-4 p-3">
                            <!-- Customer Section -->
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label>Customer Name</label>
                                    <select id="customerSelect" class="form-control" name="member_id">
                                        <option value="">-- Select Customer --</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                                data-address="{{ $customer->address }}"
                                                data-contact="{{ $customer->phone }}">
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Customer ID</label>
                                    <input type="text" id="customerId" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label>Address</label>
                                    <input type="text" id="customerAddress" class="form-control" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Contact No</label>
                                    <div class="input-group">
                                        <input type="text" id="customerContact" class="form-control"
                                            placeholder="Enter contact number">
                                        <button type="button" class="btn btn-primary"
                                            onclick="addMember('{{ route('member.store') }}')">
                                            <i class="fas fa-plus"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Section -->
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label>Product</label>
                                    <select id="productSelect" class="form-control">
                                        <option value="">-- Select Product --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                data-sell_price="{{ $product->sell_price }}">
                                                {{ $product->name }} ({{ $product->sell_price }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Quantity</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="qty" value="1"
                                            min="1">
                                        <button type="button" class="btn btn-success" id="addProduct">
                                            <i class="fas fa-plus"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Table -->
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered" id="productsTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Product Name</th>
                                            <th>S.P</th>
                                            <th>Qty</th>
                                            <th>SubTotal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between mt-2">
                                <div>Total Items: <strong id="totalItems">0</strong></div>
                                <div>Total Amount: <strong id="totalAmount">0.00</strong></div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel -->
                    <div class="col-md-4">
                        <div class="card mb-4 p-3">
                            <div class="mb-2">
                                <label>Invoice No:</label>
                                <span class="badge bg-warning">IN00000502</span>
                            </div>
                            <div class="mb-2">
                                <label>Cash</label>
                                <input type="text" id="cashInput" class="form-control" value="0.00" name="pay">
                            </div>
                            <div class="mb-2">
                                <label>Balance</label>
                                <input type="text" id="balanceInput" class="form-control text-danger" value="0.00"
                                    readonly>
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" type="submit"
                                    onclick="document.getElementById('saleForm').action='{{ route('cashier.store') }}'; document.getElementById('saleForm').insertAdjacentHTML('beforeend','<input type=hidden name=action value=save>');">Save</button>
                                <button class="btn btn-success" type="submit"
                                    onclick="document.getElementById('saleForm').action='{{ route('cashier.store') }}'; document.getElementById('saleForm').insertAdjacentHTML('beforeend','<input type=hidden name=action value=print>');">Print</button>
                                <button class="btn btn-secondary">Find</button> <button class="btn btn-info">Adv</button>
                                <button class="btn btn-warning">Ret</button> <button class="btn btn-danger">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @includeIf('member.form')
@endsection

@push('scripts')
    <script>
        const customers = @json($customers);

        // Customer select
        document.getElementById('customerSelect').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            document.getElementById('customerId').value = selected.value;
            document.getElementById('customerAddress').value = selected.getAttribute('data-address') || '';
            document.getElementById('customerContact').value = selected.getAttribute('data-contact') || '';
        });

        // Contact auto-fill
        document.getElementById('customerContact').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const contact = this.value.trim();
                const customer = customers.find(c => c.phone === contact);
                if (customer) {
                    document.getElementById('customerId').value = customer.id;
                    document.getElementById('customerAddress').value = customer.address;
                    document.getElementById('customerSelect').value = customer.id;
                } else {
                    document.getElementById('customerId').value = '';
                    document.getElementById('customerAddress').value = '';
                    document.getElementById('customerSelect').value = '';
                }
            }
        });

        // Add product
        document.getElementById('addProduct').addEventListener('click', function() {
            const productSelect = document.getElementById('productSelect');
            const qtyInput = document.getElementById('qty');
            const tableBody = document.querySelector('#productsTable tbody');

            const selected = productSelect.options[productSelect.selectedIndex];
            const productId = selected.value;
            const productName = selected.text;
            const sellPrice = parseFloat(selected.getAttribute('data-sell_price')) || 0;
            const qty = parseInt(qtyInput.value) || 1;

            if (!productId) return alert('Please select a product.');

            const subtotal = sellPrice * qty;

            const row = document.createElement('tr');
            row.innerHTML = `
        <td>${productId}<input type="hidden" name="products[${productId}][id]" value="${productId}"></td>
        <td>${productName}</td>
        <td>${sellPrice.toFixed(2)}<input type="hidden" name="products[${productId}][sale_price]" value="${sellPrice.toFixed(2)}"></td>
        <td>${qty}<input type="hidden" name="products[${productId}][amount]" value="${qty}"></td>
        <td class="subtotal">${subtotal.toFixed(2)}<input type="hidden" name="products[${productId}][sub_total]" value="${subtotal.toFixed(2)}"></td>
        <td><button type="button" class="btn btn-sm btn-danger removeRow">X</button></td>
    `;
            tableBody.appendChild(row);
            qtyInput.value = 1;
            updateTotals();
        });

        // Remove row
        document.querySelector('#productsTable').addEventListener('click', function(e) {
            if (e.target.classList.contains('removeRow')) {
                e.target.closest('tr').remove();
                updateTotals();
            }
        });

        // Totals & balance
        function updateTotals() {
            let totalAmount = 0,
                totalItems = 0;
            const rows = document.querySelectorAll('#productsTable tbody tr');
            rows.forEach(row => {
                const subtotal = parseFloat(row.querySelector('.subtotal').textContent) || 0;
                const qty = parseInt(row.querySelector('td:nth-child(4)').textContent) || 0;
                totalAmount += subtotal;
                totalItems += qty;
            });

            document.getElementById('totalItems').textContent = totalItems;
            document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
            document.getElementById('totalItemInput').value = totalItems;
            document.getElementById('totalPriceInput').value = totalAmount.toFixed(2);

            updateBalance();
        }

        function updateBalance() {
            const cash = parseFloat(document.getElementById('cashInput').value) || 0;
            const total = parseFloat(document.getElementById('totalAmount').textContent) || 0;
            const balance = cash - total;
            const balanceInput = document.getElementById('balanceInput');
            balanceInput.value = balance.toFixed(2);
            balanceInput.classList.toggle('text-danger', balance < 0);
            balanceInput.classList.toggle('text-success', balance >= 0);
        }

        document.getElementById('cashInput').addEventListener('input', updateBalance);
    </script>
@endpush
