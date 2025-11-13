<!-- Kitchen Modal -->
<div class="modal fade" id="kitchenModal" tabindex="-1" aria-labelledby="kitchenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="kitchenForm">
            @csrf
            @method('POST')

            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add Kitchen Item</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <!-- Item Name -->
                    <div class="form-group">
                        <label for="item_name">Item Name</label>
                        <input type="text" name="item_name" id="item_name" class="form-control" placeholder="Enter Item Name" required>
                    </div>

                    <!-- Quantity -->
                    <div class="form-group">
                        <label for="qty">Quantity</label>
                        <input type="number" name="qty" id="qty" class="form-control" placeholder="Enter Quantity" required>
                    </div>

                    <!-- Unit -->
                    <div class="form-group">
                        <label for="unit">Unit</label>
                        <input type="text" name="unit" id="unit" class="form-control" placeholder="e.g., kg, L, pcs" required>
                    </div>

                    <!-- Issue Date -->
                    <div class="form-group">
                        <label for="issue_date">Issue Date</label>
                        <input type="date" name="issue_date" id="issue_date" class="form-control" required>
                    </div>

                    <!-- Issued By -->
                    <div class="form-group">
                        <label for="issued_by">Issued By</label>
                        <input type="text" name="issued_by" id="issued_by" class="form-control" placeholder="Enter Name of Person Issuing" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fas fa-times-circle"></i> Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Item
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
