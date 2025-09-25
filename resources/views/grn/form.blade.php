@extends('layouts.master')

@section('title')
    <h3 class="mb-0">{{ $menu }}</h3>
@endsection

@section('content')
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Supermarket GRN (Goods Received Note)</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<style>
/* --- Existing styles --- */
body { font-family: Arial, sans-serif; margin:20px; background:#f9f9f9; }
.container { max-width:1200px; margin:auto; }
.card { background:#fff; padding:20px; border-radius:8px; box-shadow:0 0 6px rgba(0,0,0,0.1); }
header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
h1 { margin:0; color:#2c3e50; }
.small { font-size:0.85rem; color:#555; }
.grid { display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-top:15px; }
.field { margin-bottom:10px; }
.field label { display:block; font-size:0.85rem; margin-bottom:4px; font-weight:bold; color:#333; }
input, select, textarea { width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box; }
table { width:100%; border-collapse:collapse; margin-top:15px; }
th, td { border:1px solid #ccc; padding:8px; text-align:center; }
th { background-color:#f2f2f2; font-weight:bold; }
tfoot td { font-weight:bold; background:#f0f0f0; }
button { margin:5px; padding:8px 16px; cursor:pointer; border:none; border-radius:4px; font-weight:bold; transition: background-color 0.3s; }
#addRow { background-color:#3498db; color:white; }
#addRow:hover { background-color:#2980b9; }
.secondary { background:#ecf0f1; color:#2c3e50; }
.secondary:hover { background:#d5dbdb; }
.warn { background:#e74c3c; color:white; }
.warn:hover { background:#c0392b; }
.actions { margin-top:10px; display:flex; flex-wrap:wrap; gap:10px; }
.totals { display:flex; gap:20px; margin-top:15px; }
.box { background:#f4f4f4; padding:10px; border-radius:6px; text-align:center; flex:1; }
.footer { margin-top:30px; }
.signatures { display:flex; justify-content:space-around; margin-top:20px; }
.signatures .line { width:200px; border-bottom:1px solid #000; margin:5px; }
.pdf-status { margin-top:10px; padding:10px; border-radius:4px; text-align:center; display:none; }
.success { background-color:#d4edda; color:#155724; border:1px solid #c3e6cb; }
.error { background-color:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
@media print {
    body { margin:0; background:white; }
    .container { max-width:100%; box-shadow:none; }
    .no-print { display:none !important; }
    .card { box-shadow:none; padding:10px; }
    button { display:none !important; }
}
</style>
</head>
<body>
<div class="container">
    <div class="card">
        <header>
            <div>
                <h1>Goods Received Note (GRN)</h1>
                <div class="small">Supermarket GRN — record goods received from a supplier</div>
            </div>
            <div class="no-print">
                <a href="{{ route('grn.index') }}"><button class="secondary">← Back</button></a>
                <button id="printBtn">Save as PDF</button>
            </div>
        </header>

        <div id="pdfStatus" class="pdf-status"></div>

        <form method="POST" action="{{ route('grn.store') }}">
            @csrf
            <input type="hidden" name="grn_no" id="grnNo">

            <div class="grid">
                <div class="card inner">
                    <div class="meta">
                        <div class="field">
                            <label>Date</label>
                            <input id="grnDate" type="date" name="date" required />
                        </div>
                        <div class="field">
                            <label>Supplier Name</label>
                            <select id="supplier" name="supplier" required>
                                <option value="">-- Select Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>PO No</label>
                            <input id="poNo" type="text" name="po_no" placeholder="PO-123" />
                        </div>
                        <div class="field">
                            <label>Invoice No</label>
                            <input id="invNo" type="text" name="invoice_no" placeholder="INV-456" />
                        </div>
                    </div>
                </div>

                <div class="card inner">
                    <div class="meta">
                        <div class="field">
                            <label>General Remarks</label><br />
                            <textarea id="generalRemarks" name="general_remarks" rows="6" placeholder="Enter remarks here..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-wrap">
                <table id="itemsTable">
                    <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>Description</th>
                            <th>UOM</th>
                            <th>Qty Ordered</th>
                            <th>Qty Received</th>
                            <th>Qty Accepted</th>
                            <th>Qty Rejected</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                            <th>Remarks</th>
                            <th class="no-print">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8" style="text-align:right; font-weight:bold;">Grand Total</td>
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
                    <div class="small">Total Qty Received</div>
                    <div id="totalReceived">0</div>
                </div>
                <div class="box">
                    <div class="small">Total Qty Accepted</div>
                    <div id="totalAccepted">0</div>
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
                <button type="submit" class="secondary">Save GRN</button>
                <button type="button" id="resetAll" class="warn">Reset Form</button>
            </div>
        </form>
    </div>
</div>

<script>
const tbody = document.getElementById("tbody");
const totalItems = document.getElementById("totalItems");
const totalReceived = document.getElementById("totalReceived");
const totalAccepted = document.getElementById("totalAccepted");
const grandTotalCell = document.getElementById("grandTotal");
const grnNoInput = document.getElementById("grnNo");

function generateGRN() {
    const d = new Date();
    return "GRN-" + d.getFullYear() + (d.getMonth() + 1) + d.getDate() + "-" + Math.floor(Math.random() * 900 + 100);
}

function addRow(data={}) {
    const tr = document.createElement("tr");
    tr.innerHTML = `
        <td><input name="items[][code]" class="code" value="${data.code||''}"></td>
        <td><input name="items[][desc]" class="desc" value="${data.desc||''}"></td>
        <td><input name="items[][uom]" class="uom" value="${data.uom||''}"></td>
        <td><input name="items[][ordered]" class="ordered" type="number" value="${data.ordered||0}"></td>
        <td><input name="items[][received]" class="received" type="number" value="${data.received||0}"></td>
        <td><input name="items[][accepted]" class="accepted" type="number" value="${data.accepted||0}"></td>
        <td><input name="items[][rejected]" class="rejected" type="number" value="${data.rejected||0}" readonly></td>
        <td><input name="items[][price]" class="price" type="number" step="0.01" value="${data.price||0}"></td>
        <td class="total" data-value="0.00">0.00</td>
        <td><input name="items[][remarks]" class="remarks" value="${data.remarks||''}"></td>
        <td class="no-print"><button type="button" class="del warn">X</button></td>
    `;
    tr.querySelector(".del").onclick = () => { tr.remove(); recalc(); };
    tr.querySelectorAll("input").forEach(i => i.oninput = recalc);
    tbody.appendChild(tr);
    recalc();
}

function recalc() {
    const rows = tbody.querySelectorAll("tr");
    totalItems.textContent = rows.length;
    let rec=0, acc=0, gTotal=0;
    rows.forEach(r=>{
        const received = +r.querySelector(".received").value||0;
        const accepted = +r.querySelector(".accepted").value||0;
        const price = +r.querySelector(".price").value||0;
        const rejected = received - accepted;
        r.querySelector(".rejected").value = rejected;
        const total = accepted*price;
        r.querySelector(".total").textContent = total.toFixed(2);
        rec += received;
        acc += accepted;
        gTotal += total;
    });
    totalReceived.textContent = rec;
    totalAccepted.textContent = acc;
    grandTotalCell.textContent = gTotal.toFixed(2);
}

document.getElementById("addRow").onclick = () => addRow();
document.getElementById("clearRows").onclick = () => { tbody.innerHTML=""; recalc(); };
document.getElementById("resetAll").onclick = () => { tbody.innerHTML=""; recalc(); document.querySelector("form").reset(); grnNoInput.value = generateGRN(); };
window.onload = () => { grnNoInput.value = generateGRN(); document.getElementById("grnDate").value = new Date().toISOString().split("T")[0]; addRow(); };
</script>
</body>
</html>
@endsection
