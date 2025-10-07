@extends('layouts.master')

@section('title')
    <h3 class="mb-0">{{ $menu }}</h3>
@endsection

@section('content')
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
                            <input id="invNo" type="text" name="invoice_no" value="{{ $grn->invoice_no }}" />
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
                                <td><input name="items[{{ $i }}][ordered]" class="ordered" type="number"
                                        value="{{ $item->qty_ordered }}"></td>
                                <td><input name="items[{{ $i }}][received]" class="received" type="number"
                                        value="{{ $item->qty_received }}"></td>
                                <td><input name="items[{{ $i }}][accepted]" class="accepted" type="number"
                                        value="{{ $item->qty_accepted }}"></td>
                                <td><input name="items[{{ $i }}][rejected]" class="rejected" type="number"
                                        value="{{ $item->qty_rejected }}" readonly></td>
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

{{-- reuse same JS as create.blade.php --}}
<script>
    // copy addRow(), recalc(), deleteRow() etc. from create.blade.php
</script>
@endsection
