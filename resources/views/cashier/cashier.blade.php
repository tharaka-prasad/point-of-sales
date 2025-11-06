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
                                    <label>Barcode / Product</label>
                                    <input type="text" id="barcodeInput" class="form-control"
                                        placeholder="Scan or type barcode">
                                    <select id="productSelect" class="form-control mt-1">
                                        <option value="">-- Select Product --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-sell_price="{{ $product->sell_price }}"
                                                data-discount="{{ $product->discount ?? 0 }}">
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
                                            <th>Discount</th>
                                            <th>SubTotal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between mt-2">
                                <div>Total Items: <strong id="totalItems">0</strong></div>
                                <div>Total Amount: <strong id="totalAmount" step="0.01" min="0">0.00</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel -->
                    <div class="col-md-4">
                        <div class="card mb-4 p-3">
                            <div class="mb-2">
                                <label>Invoice No:</label>
                                <span class="badge bg-warning" id="invoiceNumber">IN00000502</span>
                            </div>
                            <div class="mb-2">
                                <label>Cash</label>
                                <input type="text" id="cashInput" step="0.01" min="0" class="form-control"
                                    value="0.00" name="pay">
                            </div>
                            <div class="mb-2">
                                <label>Balance</label>
                                <input type="text" id="balanceInput" step="0.01" min="0"
                                    class="form-control text-danger" value="0.00" readonly>
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" type="submit"
                                    onclick="document.getElementById('saleForm').insertAdjacentHTML('beforeend','<input type=hidden name=action value=save>');">Save</button>
                                <button class="btn btn-success" type="submit"
                                    onclick="document.getElementById('saleForm').insertAdjacentHTML('beforeend','<input type=hidden name=action value=print>');">Print</button>
                                <button type="button" id="findDraftsBtn" class="btn btn-info">
                                    <i class="bi bi-search"></i> Find Drafts
                                </button>
                                @include('cashier.draftModal')

                                <a href="{{ route('cashier.return') }}" class="btn btn-info">
                                    <i class="bi bi-search"></i> Find Returns
                                </a>


                                <button type="button" class="btn btn-danger" id="cancelButton">Cancel</button>
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
        document.addEventListener("DOMContentLoaded", function() {
            const customers = @json($customers);
            const products = @json($products);

            // Form elements
            const saleForm = document.getElementById('saleForm');
            const customerSelect = document.getElementById('customerSelect');
            const customerId = document.getElementById('customerId');
            const customerAddress = document.getElementById('customerAddress');
            const customerContact = document.getElementById('customerContact');
            const barcodeInput = document.getElementById('barcodeInput');
            const productSelect = document.getElementById('productSelect');
            const qtyInput = document.getElementById('qty');
            const addProductBtn = document.getElementById('addProduct');
            const productsTableBody = document.querySelector('#productsTable tbody');
            const totalItemsEl = document.getElementById('totalItems');
            const totalAmountEl = document.getElementById('totalAmount');
            const totalItemInput = document.getElementById('totalItemInput');
            const totalPriceInput = document.getElementById('totalPriceInput');
            const cashInput = document.getElementById('cashInput');
            const balanceInput = document.getElementById('balanceInput');
            const cancelBtn = document.getElementById('cancelButton');
            const findDraftsBtn = document.getElementById("findDraftsBtn");
            const draftModalEl = document.getElementById("draftModal");
            const draftListBody = document.getElementById("draftListBody");

            let currentDraftId = null; // store loaded draft ID

            // ------------------------------
            // Customer selection
            // ------------------------------
            customerSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                customerId.value = selected.value;
                customerAddress.value = selected.getAttribute('data-address') || '';
                customerContact.value = selected.getAttribute('data-contact') || '';
            });

            customerContact.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const contact = this.value.trim();
                    const customer = customers.find(c => c.phone == contact);
                    if (customer) {
                        customerId.value = customer.id;
                        customerAddress.value = customer.address;
                        customerSelect.value = customer.id;
                    } else {
                        customerId.value = '';
                        customerAddress.value = '';
                        customerSelect.value = '';
                    }
                }
            });

            // ------------------------------
            // Barcode and product select
            // ------------------------------
            barcodeInput.addEventListener('input', function() {
                const code = this.value.trim();
                if (!code) return;
                const product = products.find(p => String(p.code) === String(code));
                if (product) {
                    productSelect.value = product.id;
                    qtyInput.value = 1;
                }
            });

            productSelect.addEventListener('change', function() {
                const productId = this.value;
                if (!productId) return barcodeInput.value = '';
                const product = products.find(p => String(p.id) === String(productId));
                if (product) barcodeInput.value = product.code || '';
            });

            // ------------------------------
            // Add product row
            // ------------------------------
            addProductBtn.addEventListener('click', function() {
                const selected = productSelect.options[productSelect.selectedIndex];
                const productIdVal = selected.value;
                const productName = selected.text;
                const sellPrice = parseFloat(selected.getAttribute('data-sell_price')) || 0;
                const discount = parseFloat(selected.getAttribute('data-discount')) || 0;
                const qty = parseInt(qtyInput.value) || 1;
                if (!productIdVal) return alert('Please select a product.');

                const subtotal = (sellPrice - discount) * qty;
                const row = document.createElement('tr');
                row.innerHTML = `
            <td>${productIdVal}<input type="hidden" name="products[${productIdVal}][id]" value="${productIdVal}"></td>
            <td>${productName}</td>
            <td>${sellPrice.toFixed(2)}<input type="hidden" name="products[${productIdVal}][sale_price]" value="${sellPrice.toFixed(2)}"></td>
            <td>${qty}<input type="hidden" name="products[${productIdVal}][amount]" value="${qty}"></td>
            <td><input type="number" class="form-control discountInput" value="${discount}" min="0" style="width:80px"
                name="products[${productIdVal}][discount]"></td>
            <td class="subtotal">${subtotal.toFixed(2)}<input type="hidden" name="products[${productIdVal}][sub_total]" value="${subtotal.toFixed(2)}"></td>
            <td><button type="button" class="btn btn-sm btn-danger removeRow">X</button></td>`;
                productsTableBody.appendChild(row);

                // Reset input
                qtyInput.value = 1;
                productSelect.value = '';
                barcodeInput.value = '';
                updateTotals();
            });

            // ------------------------------
            // Remove product row
            // ------------------------------
            productsTableBody.addEventListener('click', function(e) {
                if (e.target.classList.contains('removeRow')) {
                    e.target.closest('tr').remove();
                    updateTotals();
                }
            });

            // ------------------------------
            // Discount change
            // ------------------------------
            productsTableBody.addEventListener('input', function(e) {
                if (e.target.classList.contains('discountInput')) {
                    const row = e.target.closest('tr');
                    const price = parseFloat(row.querySelector('td:nth-child(3)').textContent) || 0;
                    const qty = parseInt(row.querySelector('td:nth-child(4)').textContent) || 0;
                    const discount = parseFloat(e.target.value) || 0;
                    const subtotal = (price - discount) * qty;
                    row.querySelector('.subtotal').textContent = subtotal.toFixed(2);
                    row.querySelector('input[name$="[sub_total]"]').value = subtotal.toFixed(2);
                    updateTotals();
                }
            });

            // ------------------------------
            // Update totals & balance
            // ------------------------------
            function updateTotals() {
                let totalAmount = 0,
                    totalItems = 0;
                productsTableBody.querySelectorAll('tr').forEach(row => {
                    const subtotal = parseFloat(row.querySelector('.subtotal').textContent) || 0;
                    const qty = parseInt(row.querySelector('td:nth-child(4)').textContent) || 0;
                    totalAmount += subtotal;
                    totalItems += qty;
                });
                totalItemsEl.textContent = totalItems;
                totalAmountEl.textContent = totalAmount.toFixed(2);
                totalItemInput.value = totalItems;
                totalPriceInput.value = totalAmount.toFixed(2);
                updateBalance();
            }

            function updateBalance() {
                const cash = parseFloat(cashInput.value) || 0;
                const total = parseFloat(totalAmountEl.textContent) || 0;
                const balance = cash - total;
                balanceInput.value = balance.toFixed(2);
                balanceInput.classList.toggle('text-danger', balance < 0);
                balanceInput.classList.toggle('text-success', balance >= 0);
            }

            cashInput.addEventListener('input', updateBalance);

            // ------------------------------
            // Draft modal: Find Drafts
            // ------------------------------
            findDraftsBtn.addEventListener("click", function() {
                const modal = new bootstrap.Modal(draftModalEl);
                modal.show();

                draftListBody.innerHTML = `<tr>
            <td colspan="6" class="text-center text-muted py-3">
                <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                <span class="ms-2">Loading drafts...</span>
            </td>
        </tr>`;

                fetch("{{ route('cashier.drafts') }}")
                    .then(res => res.ok ? res.json() : Promise.reject(res))
                    .then(data => {
                        draftListBody.innerHTML = "";
                        if (!data.length) {
                            draftListBody.innerHTML =
                                `<tr><td colspan="6" class="text-center text-muted py-3">No draft sales found.</td></tr>`;
                            return;
                        }

                        data.forEach(draft => {
                            const row = document.createElement("tr");
                            row.innerHTML =
                                `
                        <td>${draft.id}</td>
                        <td>${draft.member ? draft.member.name : '-'}</td>
                        <td>${draft.total_item}</td>
                        <td>${parseFloat(draft.total_price).toFixed(2)}</td>
                        <td>${new Date(draft.created_at).toLocaleDateString()}</td>
                        <td><button class="btn btn-sm btn-primary loadDraftBtn" data-id="${draft.id}">Load</button></td>`;
                            draftListBody.appendChild(row);
                        });
                    })
                    .catch(err => {
                        console.error("Error fetching drafts:", err);
                        draftListBody.innerHTML =
                            `<tr><td colspan="6" class="text-center text-danger py-3">⚠️ Failed to load drafts.</td></tr>`;
                    });
            });

            // ------------------------------
            // Load Draft
            // ------------------------------
            document.addEventListener("click", function(e) {
                if (!e.target.classList.contains("loadDraftBtn")) return;

                const draftId = e.target.dataset.id;

                fetch(`{{ url('/cashier/drafts') }}/${draftId}`)
                    .then(res => res.ok ? res.json() : Promise.reject(res))
                    .then(draft => {
                        currentDraftId = draft.id;

                        // Reset form
                        saleForm.reset();
                        productsTableBody.innerHTML = '';
                        totalItemsEl.textContent = 0;
                        totalAmountEl.textContent = '0.00';
                        totalItemInput.value = 0;
                        totalPriceInput.value = 0;
                        balanceInput.value = '0.00';
                        balanceInput.classList.remove('text-success', 'text-danger');

                        // Populate customer
                        if (draft.member) {
                            customerSelect.value = draft.member.id;
                            customerId.value = draft.member.id;
                            customerAddress.value = draft.member.address;
                            customerContact.value = draft.member.phone;
                        }

                        // Populate products
                        draft.products.forEach(prod => {
                            const subtotal = (prod.sale_price - prod.discount) * prod.amount;
                            const row = document.createElement('tr');
                            row.innerHTML = `
                        <td>${prod.id}<input type="hidden" name="products[${prod.id}][id]" value="${prod.id}"></td>
                        <td>${prod.name}</td>
                        <td>${parseFloat(prod.sale_price).toFixed(2)}<input type="hidden" name="products[${prod.id}][sale_price]" value="${parseFloat(prod.sale_price).toFixed(2)}"></td>
                        <td>${prod.amount}<input type="hidden" name="products[${prod.id}][amount]" value="${prod.amount}"></td>
                        <td><input type="number" class="form-control discountInput" value="${prod.discount}" min="0" style="width:80px" name="products[${prod.id}][discount]"></td>
                        <td class="subtotal">${subtotal.toFixed(2)}<input type="hidden" name="products[${prod.id}][sub_total]" value="${subtotal.toFixed(2)}"></td>
                        <td><button type="button" class="btn btn-sm btn-danger removeRow">X</button></td>`;
                            productsTableBody.appendChild(row);
                        });

                        updateTotals();

                        // Close modal
                        const modal = bootstrap.Modal.getInstance(draftModalEl);
                        modal.hide();
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Failed to load draft.");
                    });
            });

            // ------------------------------
            // Add draft_id before submitting
            // ------------------------------
            saleForm.addEventListener('submit', function() {
                if (currentDraftId) {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'draft_id';
                    input.value = currentDraftId;
                    saleForm.appendChild(input);
                }
            });

            // ------------------------------
            // Cancel button
            // ------------------------------
            cancelBtn.addEventListener('click', function() {
                saleForm.reset();
                productsTableBody.innerHTML = '';
                totalItemsEl.textContent = 0;
                totalAmountEl.textContent = '0.00';
                totalItemInput.value = 0;
                totalPriceInput.value = 0;
                balanceInput.value = '0.00';
                balanceInput.classList.remove('text-success', 'text-danger');
                customerSelect.value = '';
                customerId.value = '';
                customerAddress.value = '';
                customerContact.value = '';
                productSelect.value = '';
                qtyInput.value = 1;
                barcodeInput.value = '';
                currentDraftId = null;
            });
        });

        document.getElementById('findReturnsBtn').addEventListener('click', function() {
            window.location.href = "{{ route('cashier.return') }}";
        });


    </script>
@endpush
