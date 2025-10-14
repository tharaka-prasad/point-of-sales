<!-- Draft Sales Modal -->
<div class="modal fade" id="draftModal" tabindex="-1" aria-labelledby="draftModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title" id="draftModalLabel">Draft Sales</h5>
        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-sm align-middle">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Member</th>
              <th>Total Items</th>
              <th>Total Price</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="draftListBody">
            <tr><td colspan="6" class="text-center text-muted">No draft sales found</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
