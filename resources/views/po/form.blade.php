@extends('layouts.master')

@section('title')
    <h3 class="mb-0">{{ $menu }}</h3>
@endsection

@section('breadcumb')
    @parent
    <li class="breadcrumb-item active" aria-current="page">{{ $menu }}</li>
@endsection

@section('content')
<div class="app-content">
    <div class="container-fluid">
        <div class="card">

            <!-- --- Inline CSS from previous template --- -->
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                    background: #f9f9f9;
                }

                .container {
                    max-width: 1200px;
                    margin: auto;
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

                .secondary:hover {
                    background: #d5dbdb;
                }

                .warn {
                    background: #e74c3c;
                    color: white;
                }

                .warn:hover {
                    background: #c0392b;
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

                .footer {
                    margin-top: 30px;
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
                    body {
                        margin: 0;
                        background: white;
                    }

                    .container {
                        max-width: 100%;
                        box-shadow: none;
                    }

                    .no-print {
                        display: none !important;
                    }

                    .card {
                        box-shadow: none;
                        padding: 10px;
                    }

                    button {
                        display: none !important;
                    }
                }
            </style>

            <header>
                <div>
                    <h1>Purchase Order (PO)</h1>
                    <div class="small">Supermarket PO — record issue for a supplier</div>
                </div>
                <div class="no-print">
                    <a href="{{ route('po.index') }}"><button class="secondary">← Back</button></a>
                </div>
            </header>

            <form method="POST" action="{{ route('po.store') }}">
                @csrf
                <div class="grid">
                    <div class="card inner">
                        <div class="meta">
                            <div class="field">
                                <label>Date</label>
                                <input id="poDate" type="date" name="date" required />
                            </div>
                            <div class="field">
                                <label>Supplier Name</label>
                                <select id="supplier" name="supplier_id" required>
                                    <option value="">-- Select Supplier --</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label>PO No</label>
                                <input id="poNo" type="text" name="po_number" readonly />
                            </div>
                        </div>
                    </div>

                    <div class="card inner">
                        <div class="meta">
                            <div class="field">
                                <label>General Remarks</label>
                                <textarea id="generalRemarks" name="description" rows="6" placeholder="Enter remarks here..."></textarea>
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
                        <tbody id="tbody"></tbody>
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
                    <button type="button" class="secondary" id="clearRows">Clear All</button>
                </div>

                <div class="totals">
                    <div class="box">
                        <div class="small">Total Items</div>
                        <div id="totalItems">0</div>
                    </div>
                    <div class="box">
                        <div class="small">Total Qty</div>
                        <div id="totalReceived">0</div>
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
                    <button type="submit" class="secondary">Save PO</button>
                    <button type="button" id="resetAll" class="warn">Reset Form</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const tbody = document.getElementById("tbody");
const totalItems = document.getElementById("totalItems");
const totalReceived = document.getElementById("totalReceived");
const grandTotalCell = document.getElementById("grandTotal");
const poNoInput = document.getElementById("poNo");

let rowIndex = 0;

// --- Generate PO Number ---
function generatePO() {
    const d = new Date();
    return "PO-" +
        d.getFullYear() +
        (d.getMonth() + 1).toString().padStart(2, '0') +
        d.getDate().toString().padStart(2, '0') +
        "-" +
        Math.floor(Math.random() * 900 + 100);
}

function addRow(data = {}) {
    const tr = document.createElement("tr");
    tr.innerHTML = `
        <td><input name="items[${rowIndex}][item_name]" class="item_name" value="${data.item_name || ''}"></td>
        <td><input name="items[${rowIndex}][category]" class="category" value="${data.category || ''}"></td>
        <td><input name="items[${rowIndex}][uom]" class="uom" value="${data.uom || ''}"></td>
        <td><input name="items[${rowIndex}][qty]" class="qty" type="number" min="1" value="${data.qty || 0}"></td>
        <td><input name="items[${rowIndex}][rate]" class="rate" type="number" step="0.01" min="0" value="${data.rate || 0}"></td>
        <td class="total" data-value="0.00">0.00</td>
        <td><input name="items[${rowIndex}][remarks]" class="remarks" value="${data.remarks || ''}"></td>
        <td class="no-print"><button type="button" class="del warn">X</button></td>
    `;
    tr.querySelector(".del").onclick = () => { tr.remove(); recalc(); };
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
document.getElementById("clearRows").onclick = () => { tbody.innerHTML = ""; recalc(); };
document.getElementById("resetAll").onclick = () => {
    tbody.innerHTML = "";
    recalc();
    document.querySelector("form").reset();
    poNoInput.value = generatePO();
    rowIndex = 0;
    addRow();
};

// --- Initialize form on load ---
window.onload = () => {
    poNoInput.value = generatePO(); // ✅ ensures po_number is set
    document.getElementById("poDate").value = new Date().toISOString().split("T")[0];
    addRow();
};
</script>
@endsection
