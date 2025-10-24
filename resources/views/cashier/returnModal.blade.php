<div class="modal-body">
    <!-- Step 1: Select Customer -->
    <div class="mb-3">
        <label>Select Customer</label>
        <select id="returnCustomerSelect" class="form-control">
            <option value="">-- Select Customer --</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
            @endforeach
        </select>
        <small class="text-muted">
            Customer balance: Rs. <span id="returnCustomerBalance">0.00</span>
        </small>
    </div>

    <!-- Step 2: Select Sale -->
    <div class="mb-3">
        <label>Select Sale</label>
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
                    <th>Returnable Qty</th>
                    <th>Return Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" class="text-center text-muted py-3">
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
