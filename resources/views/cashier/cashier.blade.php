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
            <div class="row">
                <!-- Left Panel -->
                <div class="col-md-8">
                    <div class="card mb-4 p-3">
                        <!-- Customer Section -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label>Customer Name</label>
                                <select id="customerSelect" class="form-control">
                                    <option value="">-- Select Customer --</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                                data-name="{{ $customer->name }}"
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
                                    <input type="text" id="customerContact" class="form-control" placeholder="Enter contact number">
                                    <button type="button" class="btn btn-primary" onclick="addMember('{{ route('member.store') }}')">
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
                                    <input type="number" class="form-control" id="qty" value="1" min="1">
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
                            <label>Cheque Details</label>
                            <input type="text" class="form-control mb-1" placeholder="Chq #">
                            <input type="date" class="form-control mb-1" value="{{ date('Y-m-d') }}">
                            <input type="text" class="form-control mb-1" placeholder="Amount">
                        </div>
                        <div class="mb-2">
                            <label>Card Details</label>
                            <input type="text" class="form-control mb-1" placeholder="Card No">
                            <input type="text" class="form-control mb-1" placeholder="Cred. Amt">
                        </div>
                        {{-- <div class="mb-2">
                            <label>Advance</label>
                            <input type="text" class="form-control" value="0.00">
                        </div> --}}
                        <div class="mb-2">
                            <label>Cash</label>
                            <input type="text" class="form-control" value="0.00">
                        </div>
                        <div class="mb-2">
                            <label>Balance</label>
                            <input type="text" class="form-control text-danger" value="0.00">
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="submit">Save</button>
                            <button type="button" class="btn btn-secondary">Find</button>
                            <button type="button" class="btn btn-info">Adv</button>
                            <button type="button" class="btn btn-warning">Ret</button>
                            <button type="button" class="btn btn-danger">Cancel</button>
                            <button type="button" class="btn btn-success">Print</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@includeIf('member.form') <!-- Modal for Add Customer -->
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
            e.preventDefault(); // form submit wenna nawaththanna
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

// Add Customer modal
function addMember(url) {
    const modalEl = document.getElementById('modalForm');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    modalEl.querySelector('.modal-title').textContent = 'Add Customer';
    const form = modalEl.querySelector('form');
    form.reset();
    form.setAttribute('action', url);
    form.querySelector('[name=_method]').value = 'POST';

    $(form).off('submit').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            bootstrap.Modal.getInstance(modalEl).hide();
            alert('Customer added successfully!');

            const select = document.getElementById('customerSelect');
            const option = document.createElement('option');
            option.value = data.id;
            option.text = data.name;
            option.setAttribute('data-address', data.address);
            option.setAttribute('data-contact', data.phone);
            select.appendChild(option);

            select.value = data.id;
            select.dispatchEvent(new Event('change'));
            customers.push(data);
        })
        .catch(err => {
            console.error(err);
            alert('Failed to save customer.');
        });
    });
}

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

    if (!productId) {
        alert('Please select a product.');
        return;
    }

    const subtotal = sellPrice * qty;

    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${productId}</td>
        <td>${productName}</td>
        <td>${sellPrice.toFixed(2)}</td>
        <td>${qty}</td>
        <td class="subtotal">${subtotal.toFixed(2)}</td>
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

// Update totals
function updateTotals() {
    let totalItems = 0;
    let totalAmount = 0;

    document.querySelectorAll('#productsTable tbody tr').forEach(row => {
        const qty = parseInt(row.children[3].textContent) || 0;
        const subtotal = parseFloat(row.querySelector('.subtotal').textContent) || 0;
        totalItems += qty;
        totalAmount += subtotal;
    });

    document.getElementById('totalItems').textContent = totalItems;
    document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
}
</script>
@endpush
