@extends('layouts.master')

@section('title')
    <h3 class="mb-0">{{ $menu }}</h3>
@endsection

@section('content')
    <div class="container mt-4">
        <form action="{{ route('grn.store') }}" method="POST">
            @csrf

            {{-- Supplier --}}
            <div class="mb-3">
                <label for="supplier_id" class="form-label">Supplier</label>
                <select name="supplier_id" id="supplier_id" class="form-control" required>
                    <option value="">-- Select Supplier --</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Date --}}
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}"
                    required>
            </div>

            {{-- Remarks --}}
            <div class="mb-3">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea name="remarks" id="remarks" class="form-control"></textarea>
            </div>

            <hr>

            {{-- Products Table --}}
            <h5>GRN Items</h5>
            <table class="table table-bordered" id="items_table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Batch</th>
                        <th>Expiry</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                        <th><button type="button" class="btn btn-success btn-sm" id="addRow">+</button></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="items[0][product_id]" class="form-control" required>
                                <option value="">-- Select Product --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" name="items[0][batch_number]" class="form-control"></td>
                        <td><input type="date" name="items[0][expiry_date]" class="form-control"></td>
                        <td><input type="number" name="items[0][quantity]" class="form-control qty" min="1"
                                value="1" required></td>
                        <td><input type="number" name="items[0][unit_price]" class="form-control price" step="0.01"
                                value="0" required></td>
                        <td><input type="text" class="form-control total" readonly></td>
                        <td><button type="button" class="btn btn-danger btn-sm removeRow">x</button></td>
                    </tr>
                </tbody>
            </table>

            {{-- Submit --}}
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Save GRN</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        let rowCount = 1;

        document.getElementById('addRow').addEventListener('click', function() {
            let tableBody = document.querySelector('#items_table tbody');
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
        <td>
            <select name="items[${rowCount}][product_id]" class="form-control" required>
                <option value="">-- Select Product --</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="text" name="items[${rowCount}][batch_number]" class="form-control"></td>
        <td><input type="date" name="items[${rowCount}][expiry_date]" class="form-control"></td>
        <td><input type="number" name="items[${rowCount}][quantity]" class="form-control qty" min="1" value="1" required></td>
        <td><input type="number" name="items[${rowCount}][unit_price]" class="form-control price" step="0.01" value="0" required></td>
        <td><input type="text" class="form-control total" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm removeRow">x</button></td>
    `;
            tableBody.appendChild(newRow);
            rowCount++;
        });

        // Remove row
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeRow')) {
                e.target.closest('tr').remove();
            }
        });

        // Auto-calc totals
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('qty') || e.target.classList.contains('price')) {
                let row = e.target.closest('tr');
                let qty = row.querySelector('.qty').value || 0;
                let price = row.querySelector('.price').value || 0;
                row.querySelector('.total').value = (qty * price).toFixed(2);
            }
        });
    </script>
@endsection
