@extends('layouts.master')

@section('title')
    <h3 class="mb-0">{{ $menu }}</h3>
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <header>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width,initial-scale=1" />
                <title>Supermarket GRN (Goods Received Note)</title>
                <div class="no-print">
                    <a href="{{ route('grn.index') }}"><button class="secondary">← Back</button></a>
                    <button onclick="window.print()">Print</button>
                </div>
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
            </header>

            <form>
                <div class="grid">
                    <div class="card inner">
                        <div class="meta">
                            <div class="field">
                                <label>Date</label>
                                <input type="date" value="{{ $grn->date }}" disabled />
                            </div>
                            <div class="field">
                                <label>Supplier Name</label>
                                <input type="text" value="{{ $grn->supplier->supplier_name }}" disabled />
                            </div>
                            <div class="field">
                                <label>PO No</label>
                                <input type="text" value="{{ $grn->po_no }}" disabled />
                            </div>
                            <div class="field">
                                <label>Invoice No</label>
                                <input type="text" value="{{ $grn->invoice_no }}" disabled />
                            </div>
                        </div>
                    </div>

                    <div class="card inner">
                        <div class="meta">
                            <div class="field">
                                <label>General Remarks</label><br />
                                <textarea rows="6" disabled>{{ $grn->general_remarks }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-wrap">
                    <table>
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($grn->items as $index => $item)
                                <tr>
                                    <td><input type="text" value="{{ $item->item_code ?? '-' }}" disabled /></td>
                                    <td><input type="text" value="{{ $item->description ?? '-' }}" disabled /></td>
                                    <td><input type="text" value="{{ $item->uom ?? '-' }}" disabled /></td>
                                    <td><input type="number" value="{{ $item->qty_ordered ?? 0 }}" disabled /></td>
                                    <td><input type="number" value="{{ $item->qty_received ?? 0 }}" disabled /></td>
                                    <td><input type="number" value="{{ $item->qty_accepted ?? 0 }}" disabled /></td>
                                    <td><input type="number" value="{{ $item->qty_rejected ?? 0 }}" disabled /></td>
                                    <td><input type="number"
                                            value="{{ number_format($item->unit_price ?? 0, 2) }}"disabled /></td>
                                    <td><input type="number" value="{{ $item->total ?? 0 }}" disabled /></td>

                                    <td><input type="text" value="{{ $item->remarks ?? '-' }}" disabled /></td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="8" align="right"><strong>Grand Total</strong></td>
                                <td>
                                    <input type="number"
                                        value="{{ number_format($grn->grn_total, 2) }}"
                                        disabled />
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>

                    </table>
                </div>

                <div class="totals">
                    <div class="box">
                        <div class="small">Total Items</div>
                        <div>{{ $grn->items->count() }}</div>
                    </div>
                    <div class="box">
                        <div class="small">Total Qty Received</div>
                        <div>{{ $grn->items->sum('qty_received') }}</div>
                    </div>
                    <div class="box">
                        <div class="small">Total Qty Accepted</div>
                        <div>{{ $grn->items->sum('qty_accepted') }}</div>
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
            </form>
        </div>
    </div>
@endsection
