@extends('layouts.master')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>Fund Return</h4>
        </div>
        <div class="card-body">

            <!-- Message -->
            <div id="returnMsg"></div>

            <!-- Step 1: Select Customer -->
            <div class="mb-3">
                <label class="form-label">Select Customer</label>
                <select id="returnCustomerSelect" class="form-control">
                    <option value="">-- Select Customer --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
                <small class="text-muted d-block mt-1">
                    Sale Total: Rs. <span id="saleTotal">0.00</span>
                </small>
            </div>

            <!-- Step 2: Select Sale -->
            <div class="mb-3">
                <label class="form-label">Select Sale</label>
                <select id="returnSaleSelect" class="form-control" disabled>
                    <option value="">-- Select Sale --</option>
                </select>
            </div>

            <!-- Step 3: Returnable Products -->
            <div class="table-responsive mb-3">
                <table class="table table-bordered" id="returnProductsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Product</th>
                            <th>Sale Price</th>
                            <th>Returnable Qty</th>
                            <th>Return Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                Select a sale to view products.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Step 4: Totals -->
            <div class="border-top pt-3">
                <h5 class="text-end">Return Amount: Rs. <span id="returnTotalAmount">0.00</span></h5>
                <h5 class="text-end">Updated Balance: Rs. <span id="updatedBalance">0.00</span></h5>
            </div>

            <!-- Step 5: Action -->
            <div class="mt-3 text-end">
                <button class="btn btn-success" id="confirmReturnBtn">Fund Return</button>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerSelect = document.getElementById('returnCustomerSelect');
    const saleSelect = document.getElementById('returnSaleSelect');
    const productsTableBody = document.querySelector('#returnProductsTable tbody');
    const saleTotalEl = document.getElementById('saleTotal');
    const returnTotalAmountEl = document.getElementById('returnTotalAmount');
    const updatedBalanceEl = document.getElementById('updatedBalance');
    const confirmReturnBtn = document.getElementById('confirmReturnBtn');
    const returnMsgEl = document.getElementById('returnMsg');

    let saleTotal = 0;

    // Show messages
    function showMessage(msg, type = 'success') {
        returnMsgEl.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
        setTimeout(() => returnMsgEl.innerHTML = '', 3000);
    }

    // Fetch sales for selected customer
    async function fetchCustomerSales(customerId) {
        const res = await fetch(`/cashier/returns/${customerId}/sales`);
        const data = await res.json();

        saleSelect.innerHTML = `<option value="">-- Select Sale --</option>`;
        saleSelect.disabled = true;
        productsTableBody.innerHTML =
            `<tr><td colspan="5" class="text-center text-muted py-3">Select a sale to view products.</td></tr>`;
        returnTotalAmountEl.textContent = '0.00';
        updatedBalanceEl.textContent = '0.00';
        saleTotalEl.textContent = '0.00';

        if (!data.sales.length) {
            saleSelect.innerHTML = `<option value="">No completed sales found</option>`;
            return;
        }

        data.sales.forEach(sale => {
            const option = document.createElement('option');
            option.value = sale.id;
            option.textContent = `Sale #${sale.id} - Rs. ${parseFloat(sale.total_price).toFixed(2)}`;
            option.dataset.total = parseFloat(sale.total_price);
            saleSelect.appendChild(option);
        });

        saleSelect.disabled = false;
    }

    // Fetch returnable products for selected sale
    async function fetchSaleProducts(saleId) {
        const res = await fetch(`/cashier/returns/sale/${saleId}`);
        const products = await res.json();

        productsTableBody.innerHTML = '';

        if (!products.length) {
            productsTableBody.innerHTML =
                `<tr><td colspan="5" class="text-center text-muted py-3">No returnable products.</td></tr>`;
            return;
        }

        products.forEach(prod => {
            const salePrice = parseFloat(prod.sale_price) || 0;
            const returnableQty = parseFloat(prod.returnable_qty) || 0;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${prod.product_name}</td>
                <td>${salePrice.toFixed(2)}</td>
                <td>${returnableQty}</td>
                <td>
                    <input
                        type="number"
                        min="0"
                        max="${returnableQty}"
                        value="0"
                        class="form-control returnQtyInput"
                        data-price="${salePrice}"
                        data-product-id="${prod.product_id}">
                </td>
                <td class="subtotal">0.00</td>
            `;
            productsTableBody.appendChild(row);
        });

        updateTotals();
    }

    // Update totals
    function updateTotals() {
        let total = 0;
        productsTableBody.querySelectorAll('tr').forEach(row => {
            const qtyInput = row.querySelector('.returnQtyInput');
            const subtotalEl = row.querySelector('.subtotal');
            if (!qtyInput) return;

            let qty = parseFloat(qtyInput.value) || 0;
            const maxQty = parseFloat(qtyInput.max) || 0;
            if (qty < 0) qty = 0;
            if (qty > maxQty) qty = maxQty;
            qtyInput.value = qty;

            const price = parseFloat(qtyInput.dataset.price) || 0;
            const subtotal = qty * price;
            subtotalEl.textContent = subtotal.toFixed(2);
            total += subtotal;
        });

        returnTotalAmountEl.textContent = total.toFixed(2);
        const updatedBalance = saleTotal - total;
        updatedBalanceEl.textContent = updatedBalance.toFixed(2);
        updatedBalanceEl.classList.toggle('text-danger', updatedBalance < 0);
        updatedBalanceEl.classList.toggle('text-success', updatedBalance >= 0);
    }

    // Event listeners
    customerSelect.addEventListener('change', function() {
        if (!this.value) return;
        fetchCustomerSales(this.value);
    });

    saleSelect.addEventListener('change', function() {
        saleTotal = parseFloat(this.selectedOptions[0].dataset.total) || 0;
        saleTotalEl.textContent = saleTotal.toFixed(2);
        productsTableBody.innerHTML =
            `<tr><td colspan="5" class="text-center text-muted py-3">Loading products...</td></tr>`;
        returnTotalAmountEl.textContent = '0.00';
        updatedBalanceEl.textContent = saleTotal.toFixed(2);
        if (!this.value) return;
        fetchSaleProducts(this.value);
    });

    productsTableBody.addEventListener('input', function(e) {
        if (e.target.classList.contains('returnQtyInput')) updateTotals();
    });

    confirmReturnBtn.addEventListener('click', async function() {
        const saleId = saleSelect.value;
        if (!saleId) return showMessage('Please select a sale.', 'danger');

        const products = [];
        productsTableBody.querySelectorAll('.returnQtyInput').forEach(input => {
            const qty = parseFloat(input.value) || 0;
            if (qty > 0) {
                products.push({
                    product_id: input.dataset.productId,
                    return_qty: qty,
                });
            }
        });

        if (!products.length) return showMessage('Please enter return quantities.', 'danger');

        try {
            const res = await fetch(`/cashier/return-store`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ sale_id: saleId, products })
            });
            const data = await res.json();
            if (data.success) {
                showMessage(`Return Successful! Rs. ${data.total_return.toFixed(2)} refunded. Updated Sale Total: Rs. ${data.updated_balance.toFixed(2)}`, 'success');
                setTimeout(() => location.reload(), 2000);
            } else {
                showMessage('Error: ' + data.message, 'danger');
            }
        } catch(err) {
            console.error(err);
            showMessage('Something went wrong.', 'danger');
        }
    });
});
</script>
@endpush
