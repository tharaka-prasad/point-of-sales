@extends('layouts.master')

@section('title')
    <h3 class="mb-0">{{ $menu }}</h3>
@endsection

@section('breadcumb')
    @parent <li class="breadcrumb-item active" aria-current="page">{{ $menu }}</li>
@endsection

@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="card">

                ```
                <style>
                    /* Same inline CSS for consistent design */
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                        background: #f9f9f9;
                    }

                    .card {
                        background: #fff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
                    }

                    header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 20px;
                    }

                    h1 {
                        margin: 0;
                        color: #2c3e50;
                    }

                    .small {
                        font-size: 0.85rem;
                        color: #555;
                    }

                    .grid {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 15px;
                        margin-top: 15px;
                    }

                    .field {
                        margin-bottom: 10px;
                    }

                    .field label {
                        display: block;
                        font-size: 0.85rem;
                        margin-bottom: 4px;
                        font-weight: bold;
                        color: #333;
                    }

                    input,
                    select,
                    textarea {
                        width: 100%;
                        padding: 8px;
                        border: 1px solid #ddd;
                        border-radius: 4px;
                        box-sizing: border-box;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 15px;
                    }

                    th,
                    td {
                        border: 1px solid #ccc;
                        padding: 8px;
                        text-align: center;
                    }

                    th {
                        background-color: #f2f2f2;
                        font-weight: bold;
                    }

                    tfoot td {
                        font-weight: bold;
                        background: #f0f0f0;
                    }

                    button {
                        margin: 5px;
                        padding: 8px 16px;
                        cursor: pointer;
                        border: none;
                        border-radius: 4px;
                        font-weight: bold;
                        transition: background-color 0.3s;
                    }

                    #addRow {
                        background-color: #3498db;
                        color: white;
                    }

                    #addRow:hover {
                        background-color: #2980b9;
                    }

                    .secondary {
                        background: #ecf0f1;
                        color: #2c3e50;
                    }

                    .warn {
                        background: #e74c3c;
                        color: white;
                    }

                    .totals {
                        display: flex;
                        gap: 20px;
                        margin-top: 15px;
                    }

                    .box {
                        background: #f4f4f4;
                        padding: 10px;
                        border-radius: 6px;
                        text-align: center;
                        flex: 1;
                    }

                    .signatures {
                        display: flex;
                        justify-content: space-around;
                        margin-top: 20px;
                    }

                    .signatures .line {
                        width: 200px;
                        border-bottom: 1px solid #000;
                        margin: 5px;
                    }

                    @media print {
                        .no-print {
                            display: none !important;
                        }
                    }
                </style>

                <header>
                    <div>
                        <h1>Edit Purchase Order (PO)</h1>
                        <div class="small">Update supplier and item details</div>
                    </div>
                    <div class="no-print">
                        <a href="{{ route('po.index') }}"><button class="secondary">← Back</button></a>
                    </div>
                </header>

                <form method="POST" action="{{ route('po.update', $po->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid">
                        <div class="card inner">
                            <div class="meta">

                                <div class="field">
                                    <label>Date</label>
                                    <input id="poDate" type="date" name="date"
                                        value="{{ $po->date }}"required />
                                </div>

                                <div class="field">
                                    <label>Date</label>
                                    <input id="poDate" type="date" name="date"
                                        value="{{ \Carbon\Carbon::parse($po->date)->format('Y-m-d') }}"required />
                                </div>

                                <div class="field">
                                    <label>Supplier Name</label>
                                    <select id="supplier" name="supplier_id" required>
                                        <option value="">-- Select Supplier --</option>
                                        @foreach ($suppliers as $supplier)
                                            <option
                                                value="{{ $supplier->id }}"{{ $po->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->supplier_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label>PO No</label>
                                    <input id="poNo" type="text" name="po_number" value="{{ $po->po_number }}"
                                        readonly />
                                </div>
                            </div>
                        </div>

                        <div class="card inner">
                            <div class="meta">
                                <div class="field">
                                    <label>General Remarks</label>
                                    <textarea id="generalRemarks" name="description" rows="6">{{ $po->description }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table id="itemsTable">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>UOM</th>
                                    <th>Qty</th>
                                    <th>Rate</th>
                                    <th>Total Price</th>
                                    <th>Remarks</th>
                                    <th class="no-print">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                @foreach ($po->items as $index => $item)
                                    <tr>
                                        <td><input name="items[{{ $index }}][item_name]"
                                                value="{{ $item->item_name }}"></td>
                                        <td><input name="items[{{ $index }}][category]"
                                                value="{{ $item->category }}"></td>
                                        <td><input name="items[{{ $index }}][uom]" value="{{ $item->uom }}">
                                        </td>
                                        <td><input name="items[{{ $index }}][qty]" class="qty" type="number"
                                                value="{{ $item->qty }}"></td>
                                        <td><input name="items[{{ $index }}][rate]" class="rate" type="number"
                                                value="{{ $item->rate }}"></td>
                                        <td class="total">{{ number_format($item->qty * $item->rate, 2) }}</td>
                                        <td><input name="items[{{ $index }}][remarks]"
                                                value="{{ $item->remarks }}"></td>
                                        <td class="no-print"><button type="button" class="del warn">X</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" style="text-align:right; font-weight:bold;">Grand Total</td>
                                    <td id="grandTotal" data-value="0.00">0.00</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="actions no-print">
                        <button type="button" id="addRow">+ Add Row</button>
                    </div>

                    <div class="totals">
                        <div class="box">
                            <div class="small">Total Items</div>
                            <div id="totalItems">{{ $po->items->count() }}</div>
                        </div>
                        <div class="box">
                            <div class="small">Total Qty</div>
                            <div id="totalReceived">{{ $po->items->sum('qty') }}</div>
                        </div>
                    </div>

                    <div class="footer">
                        <div class="signatures">
                            <div class="small">Prepared By</div>
                            <div class="line"></div>
                            <div class="small">Checked By</div>
                            <div class="line"></div>
                            <div class="small">Approved By</div>
                            <div class="line"></div>
                        </div>
                    </div>

                    <div class="no-print buttons">
                        <button type="submit" class="secondary">Update PO</button>
                    </div>
                </form>
            </div>
        </div>
        ```

    </div>

    <script>
        const tbody = document.getElementById("tbody");
        const totalItems = document.getElementById("totalItems");
        const totalReceived = document.getElementById("totalReceived");
        const grandTotalCell = document.getElementById("grandTotal");
        let rowIndex = {{ $po->items->count() }};

        function addRow(data = {}) {
            const tr = document.createElement("tr");
            tr.innerHTML = `
        <td><input name="items[${rowIndex}][item_name]" value="${data.item_name || ''}"></td>
        <td><input name="items[${rowIndex}][category]" value="${data.category || ''}"></td>
        <td><input name="items[${rowIndex}][uom]" value="${data.uom || ''}"></td>
        <td><input name="items[${rowIndex}][qty]" class="qty" type="number" min="1" value="${data.qty || 0}"></td>
        <td><input name="items[${rowIndex}][rate]" class="rate" type="number" step="0.01" min="0" value="${data.rate || 0}"></td>
        <td class="total" data-value="0.00">0.00</td>
        <td><input name="items[${rowIndex}][remarks]" value="${data.remarks || ''}"></td>
        <td class="no-print"><button type="button" class="del warn">X</button></td>
    `;
            tr.querySelector(".del").onclick = () => {
                tr.remove();
                recalc();
            };
            tr.querySelectorAll(".qty, .rate").forEach(input => input.oninput = recalc);
            tbody.appendChild(tr);
            rowIndex++;
            recalc();
        }

        function recalc() {
            const rows = tbody.querySelectorAll("tr");
            totalItems.textContent = rows.length;

            let totalQty = 0;
            let gTotal = 0;

            rows.forEach(r => {
                const qty = +r.querySelector(".qty").value || 0;
                const rate = +r.querySelector(".rate").value || 0;
                const total = qty * rate;
                r.querySelector(".total").textContent = total.toFixed(2);
                totalQty += qty;
                gTotal += total;
            });

            totalReceived.textContent = totalQty;
            grandTotalCell.textContent = gTotal.toFixed(2);
        }

        document.getElementById("addRow").onclick = () => addRow();
        window.onload = recalc;
    </script>
@endsection
