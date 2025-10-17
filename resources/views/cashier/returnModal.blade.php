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
    </div>

    <!-- Step 2: Select Sale -->
    <div class="mb-3">
        <label>Select Sale</label>
        <select id="returnSaleSelect" class="form-control" disabled>
            <option value="">-- Select Sale --</option>
        </select>
    </div>

    <!-- Step 3: Product list -->
    <div class="table-responsive">
        <table class="table table-bordered" id="returnProductsTable">
            <thead class="table-dark">
                <tr>
                    <th>Product</th>
                    <th>Returnable Qty</th>
                    <th>Return Qty</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" class="text-center text-muted py-3">
                        Select a sale to view products.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
