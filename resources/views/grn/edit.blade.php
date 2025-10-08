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

            .actions {
                margin-top: 10px;
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
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

            .pdf-status {
                margin-top: 10px;
                padding: 10px;
                border-radius: 4px;
                text-align: center;
                display: none;
            }

            .success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }

            .error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
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
    </head>

    <body>
        <div class="container">
            <div class="card">
                <header>
                    <div>
                        <h1>Edit GRN</h1>
                        <div class="small">Update Goods Received Note details</div>
                    </div>
                    <div class="no-print">
                        <a href="{{ route('grn.index') }}">
                            <button class="secondary">← Back</button>
                        </a>
                    </div>
                </header>

                <form method="POST" action="{{ route('grn.update', $grn->id) }}">
                    @csrf
                    @method('PUT') {{-- RESTful update method --}}

                    <input type="hidden" name="grn_no" value="{{ $grn->grn_no }}">
                    <input type="hidden" name="grn_total" id="grandTotalInput" value="{{ $grn->grn_total }}">

                    <div class="grid">
                        <div class="card inner">
                            <div class="meta">
                                <div class="field">
                                    <label>Date</label>
                                    <input id="grnDate" type="date" name="date"
                                        value="{{ \Carbon\Carbon::parse($grn->date)->format('Y-m-d') }}" required />
                                </div>
                                <div class="field">
                                    <label>Supplier Name</label>
                                    <select id="supplier" name="supplier" required>
                                        <option value="">-- Select Supplier --</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ $grn->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->supplier_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label>PO No</label>
                                    <input id="poNo" type="text" name="po_no" value="{{ $grn->po_no }}" />
                                </div>
                                <div class="field">
                                    <label>Invoice No</label>
                                    <input id="invNo" type="text" name="invoice_no"
                                        value="{{ $grn->invoice_no }}" />
                                </div>
                            </div>
                        </div>

                        <div class="card inner">
                            <div class="meta">
                                <div class="field">
                                    <label>General Remarks</label><br />
                                    <textarea id="generalRemarks" name="general_remarks" rows="6">{{ $grn->general_remarks }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table id="itemsTable">
                            <thead>
                                <tr>
                                    <th>Item Code</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>UOM</th>
                                    <th>Qty Ordered</th>
                                    <th>Qty Received</th>
                                    <th>Qty Accepted</th>
                                    <th>Qty Rejected</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                    <th class="no-print">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                @foreach ($grn->items as $i => $item)
                                    <tr>
                                        <td><input name="items[{{ $i }}][code]" class="code"
                                                value="{{ $item->product->code }}"></td>
                                        <td><input name="items[{{ $i }}][desc]" class="desc"
                                                value="{{ $item->description }}"></td>
                                        <td><input name="items[{{ $i }}][remarks]" class="remarks"
                                                value="{{ $item->remarks }}"></td>
                                        <td><input name="items[{{ $i }}][uom]" class="uom"
                                                value="{{ $item->uom }}"></td>
                                        <td><input name="items[{{ $i }}][ordered]" class="ordered"
                                                type="number" value="{{ $item->qty_ordered }}"></td>
                                        <td><input name="items[{{ $i }}][received]" class="received"
                                                type="number" value="{{ $item->qty_received }}"></td>
                                        <td><input name="items[{{ $i }}][accepted]" class="accepted"
                                                type="number" value="{{ $item->qty_accepted }}"></td>
                                        <td><input name="items[{{ $i }}][rejected]" class="rejected"
                                                type="number" value="{{ $item->qty_rejected }}" readonly></td>
                                        <td><input name="items[{{ $i }}][price]" class="price" type="number"
                                                step="0.01" value="{{ $item->unit_price }}"></td>
                                        <td class="total">{{ number_format($item->total, 2) }}</td>
                                        <td class="no-print"><button type="button" class="del warn">X</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="9" style="text-align:right; font-weight:bold;">Grand Total</td>
                                    <td id="grandTotal">{{ number_format($grn->grn_total, 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="actions no-print">
                        <button type="button" id="addRow">+ Add Row</button>
                        <button type="button" class="secondary" id="clearRows">Clear All</button>
                    </div>

                    <div class="no-print buttons">
                        <button type="submit" class="secondary">Update GRN</button>
                        <a href="{{ route('grn.index') }}">
                            <button type="button" class="warn">Cancel</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const tbody = document.getElementById("tbody");
                const grandTotalCell = document.getElementById("grandTotal");
                const grnNoInput = document.getElementById("grn_no");
                let rowIndex = tbody.querySelectorAll("tr").length;

                function generateGRN() {
                    const d = new Date();
                    return "GRN-" + d.getFullYear() + (d.getMonth() + 1) + d.getDate() + "-" + Math.floor(Math
                    .random() * 900 + 100);
                }

                function recalc() {
                    const rows = tbody.querySelectorAll("tr");
                    let gTotal = 0;

                    rows.forEach(r => {
                        const received = +r.querySelector(".received").value || 0;
                        const accepted = +r.querySelector(".accepted").value || 0;
                        const price = +r.querySelector(".price").value || 0;

                        const rejected = Math.max(received - accepted, 0);
                        r.querySelector(".rejected").value = rejected;

                        const total = accepted * price;
                        r.querySelector(".total").textContent = total.toFixed(2);

                        gTotal += total;
                    });

                    grandTotalCell.textContent = gTotal.toFixed(2);
                    document.getElementById("grandTotalInput").value = gTotal.toFixed(2);
                }

                function addRow(data = {}) {
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
            <td><input name="items[${rowIndex}][code]" class="code" value="${data.code || ''}"></td>
            <td><input name="items[${rowIndex}][desc]" class="desc" value="${data.desc || ''}"></td>
            <td><input name="items[${rowIndex}][remarks]" class="remarks" value="${data.remarks || ''}"></td>
            <td><input name="items[${rowIndex}][uom]" class="uom" value="${data.uom || ''}"></td>
            <td><input name="items[${rowIndex}][ordered]" class="ordered" type="number" value="${data.ordered || 0}"></td>
            <td><input name="items[${rowIndex}][received]" class="received" type="number" value="${data.received || 0}"></td>
            <td><input name="items[${rowIndex}][accepted]" class="accepted" type="number" value="${data.accepted || 0}"></td>
            <td><input name="items[${rowIndex}][rejected]" class="rejected" type="number" value="${data.rejected || 0}" readonly></td>
            <td><input name="items[${rowIndex}][price]" class="price" type="number" step="0.01" value="${data.price || 0}"></td>
            <td class="total" data-value="0.00">0.00</td>
            <td class="no-print"><button type="button" class="del warn">X</button></td>
        `;
                    tr.querySelector(".del").onclick = () => {
                        tr.remove();
                        recalc();
                    };
                    tr.querySelectorAll(".received, .accepted, .price").forEach(i => i.addEventListener("input",
                        recalc));
                    tbody.appendChild(tr);
                    rowIndex++;
                    recalc();
                }

                // Attach to existing rows
                tbody.querySelectorAll("tr").forEach(tr => {
                    tr.querySelector(".del").onclick = () => {
                        tr.remove();
                        recalc();
                    };
                    tr.querySelectorAll(".received, .accepted, .price").forEach(i => i.addEventListener("input",
                        recalc));
                });

                document.getElementById("addRow").onclick = () => addRow();
                document.getElementById("clearRows").onclick = () => {
                    tbody.innerHTML = "";
                    recalc();
                };

                // Initialize
                if (!grnNoInput.value) grnNoInput.value = generateGRN();
                recalc();
            });
        </script>

    </body>

    </html>
@endsection
