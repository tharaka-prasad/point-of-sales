<!-- Kitchen Modal -->
<div class="modal fade" id="kitchenModal" tabindex="-1" aria-labelledby="kitchenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="kitchenForm">
            @csrf
            <input type="hidden" name="_method" value="POST">

            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="kitchenModalLabel">Add Kitchen Item</h5>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <!-- Item Name -->
                        <div class="col-md-8">
                            <label for="item_name" class="form-label">Item Name</label>
                            <select name="item_name" id="item_name" class="form-control select2" required>
                                <option value="" disabled selected>-- Select Item --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quantity -->
                        <div class="col-md-3">
                            <label for="qty" class="form-label">Quantity</label>
                            <input type="number" step="0.01" name="qty" id="qty" class="form-control" required>
                        </div>

                        <!-- Unit -->
                        <div class="col-md-3">
                            <label for="unit" class="form-label">Unit</label>
                            <input type="text" name="unit" id="unit" class="form-control">
                        </div>

                        <!-- Issue Date -->
                        <div class="col-md-6">
                            <label for="issue_date" class="form-label">Issue Date</label>
                            <input type="date" name="issue_date" id="issue_date" class="form-control" required>
                        </div>

                        <!-- Meal Type -->
                        <div class="col-md-6">
                            <label for="meal_type" class="form-label">Meal Type</label>
                            <select name="meal_type" id="meal_type" class="form-control" required>
                                <option value="breakfast">Breakfast</option>
                                <option value="lunch">Lunch</option>
                                <option value="dinner">Dinner</option>
                                <option value="all">All</option>
                            </select>
                        </div>

                        <!-- Issued By -->
                        <div class="col-md-12">
                            <label for="issued_by" class="form-label">Issued By</label>
                            <input type="text" name="issued_by" id="issued_by" class="form-control" placeholder="Enter issuer name">
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Select2 Height Fix -->
<style>
    .select2-container .select2-selection--single {
        height: 45px !important;
        padding: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px !important;
        font-size: 16px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
    }
</style>
