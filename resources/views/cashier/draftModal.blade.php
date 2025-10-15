<!-- Draft Sales Modal -->
<div class="modal fade" id="draftModal" tabindex="-1" aria-labelledby="draftModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="draftModalLabel">Draft Sales</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Total Items</th>
                            <th>Total Price</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="draftListBody">
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                Click “Find Drafts” to load data...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <!-- Close button -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const findDraftsBtn = document.getElementById("findDraftsBtn");
    const draftModalEl = document.getElementById("draftModal");
    const draftListBody = document.getElementById("draftListBody");

    // Initialize modal
    let draftModal = bootstrap.Modal.getOrCreateInstance(draftModalEl);

    // Open modal & load drafts
    findDraftsBtn.addEventListener("click", function() {
        draftModal.show();

        draftListBody.innerHTML = `<tr>
            <td colspan="6" class="text-center py-3">
                <div class="spinner-border text-primary spinner-border-sm"></div>
                <span class="ms-2">Loading drafts...</span>
            </td>
        </tr>`;

        fetch("{{ route('cashier.drafts') }}")
            .then(res => res.ok ? res.json() : Promise.reject(res))
            .then(data => {
                draftListBody.innerHTML = "";
                if (!data.length) {
                    draftListBody.innerHTML = `<tr><td colspan="6" class="text-center py-3 text-muted">No drafts found.</td></tr>`;
                    return;
                }

                data.forEach(draft => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${draft.id}</td>
                        <td>${draft.member ? draft.member.name : '-'}</td>
                        <td>${draft.total_item}</td>
                        <td>${parseFloat(draft.total_price).toFixed(2)}</td>
                        <td>${new Date(draft.created_at).toLocaleDateString()}</td>
                        <td>
                            <button class="btn btn-sm btn-primary loadDraftBtn" data-id="${draft.id}">Load</button>
                        </td>`;
                    draftListBody.appendChild(row);
                });
            })
            .catch(err => {
                draftListBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-3">Failed to load drafts.</td></tr>`;
                console.error(err);
            });
    });

    // Load draft & hide modal
    document.addEventListener("click", function(e) {
        if (!e.target.classList.contains("loadDraftBtn")) return;

        const draftId = e.target.dataset.id;

        fetch(`{{ url('/cashier/drafts') }}/${draftId}`)
            .then(res => res.ok ? res.json() : Promise.reject(res))
            .then(draft => {
                document.dispatchEvent(new CustomEvent("draftLoaded", { detail: draft }));

                // Hide modal
                draftModal.hide();
            })
            .catch(err => {
                console.error(err);
                alert("Failed to load draft.");
            });
    });
});
</script>
@endpush
