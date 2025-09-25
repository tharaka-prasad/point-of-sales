<div class="modal fade" id="modalForm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="shiftForm">
            @csrf
            <input type="hidden" name="_method" value="POST">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    {{-- START SHIFT FIELDS --}}
                    <div class="start-fields">
                        <div class="form-group">
                            <label>Cashier</label>
                            <select name="cashier_id" class="form-control cashier-select" required>
                                <option value="">Select Cashier</option>
                                @foreach (\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Start Balance</label>
                            <input type="number" name="start_balance" class="form-control start-balance" required>
                        </div>

                        <div class="form-group">
                            <label>Start Date & Time</label>
                            <input type="datetime-local" name="start_time" class="form-control start-time"
                                value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                    </div>

                    {{-- CLOSE SHIFT FIELDS --}}
                    <div class="end-fields" style="display:none;">
                        <div class="form-group">
                            <label>Cashier</label>
                            <input type="text" class="form-control end-cashier" readonly>
                        </div>
                        <div class="form-group">
                            <label>Start Balance</label>
                            <input type="number" class="form-control end-start-balance" readonly>
                        </div>
                        <div class="form-group">
                            <label>Start Time</label>
                            <input type="text" class="form-control end-start-time" readonly>
                        </div>
                        <div class="form-group">
                            <label>End Balance</label>
                            <input type="number" step="0.01" name="end_balance" class="form-control end-balance">
                        </div>
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
