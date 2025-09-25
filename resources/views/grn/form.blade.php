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

            #printBtn {
                background-color: #3498db;
                color: white;
            }

            #printBtn:hover {
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
                        <h1>Goods Received Note (GRN)</h1>
                        <div class="small">Supermarket GRN — record goods received from a supplier</div>
                    </div>
                    <div class="no-print">
                        <a href="{{ route('grn.index') }}">
                            <button class="secondary">← Back</button>
                        </a>
                        <button id="printBtn">Save as PDF</button>
                    </div>

                </header>

                <div id="pdfStatus" class="pdf-status"></div>

                <!-- Meta Information -->
                <div class="grid">
                    <div class="card inner">
                        <div class="meta">
                            <div class="field">
                                <label>GRN No</label>
                                <input id="grnNo" type="text" />
                            </div>
                            <div class="field">
                                <label>Date</label>
                                <input id="grnDate" type="date" />
                            </div>
                            <div class="field">
                                <label>Supplier Name</label>
                                <input id="supplier" type="text" placeholder="Supplier name" />
                            </div>
                            <div class="field">
                                <label>PO No</label>
                                <input id="poNo" type="text" placeholder="PO-123" />
                            </div>
                            <div class="field">
                                <label>Invoice No</label>
                                <input id="invNo" type="text" placeholder="INV-456" />
                            </div>
                        </div>
                    </div>

                    <!-- Right-side card (originally for Department, Prepared By, Checked By) -->
                    <div class="card inner">
                        <div class="meta">
                            <div class="field">
                                <label>General Remarks</label><br />
                                <textarea id="generalRemarks" rows="6" placeholder="Enter remarks here..."></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Items Table -->
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
                    <button id="addRow">+ Add Row</button>
                    <button class="secondary" id="clearRows">Clear All</button>
                    <button class="secondary" id="exportCSV">Export CSV</button>
                    <button class="secondary" id="loadSample">Load Sample</button>
                </div>

                <!-- Totals -->
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

                <!-- Footer -->
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
                    <button id="saveLocal" class="secondary">Save Locally</button>
                    <button id="loadLocal" class="secondary">Load Last</button>
                    <button id="resetAll" class="warn">Reset Form</button>
                </div>
            </div>
        </div>

        <!-- JavaScript -->
        <script>
            const tbody = document.getElementById("tbody");
            const totalItems = document.getElementById("totalItems");
            const totalReceived = document.getElementById("totalReceived");
            const totalAccepted = document.getElementById("totalAccepted");
            const grandTotalCell = document.getElementById("grandTotal");
            const pdfStatus = document.getElementById("pdfStatus");

            function generateGRN() {
                const d = new Date();
                return "GRN-" + d.getFullYear() + (d.getMonth() + 1) + d.getDate() + "-" + Math.floor(Math.random() * 900 +
                100);
            }

            function syncCell(input) {
                input.parentElement.setAttribute("data-value", input.value);
            }

            function addRow(data = {}) {
                const tr = document.createElement("tr");
                tr.innerHTML = `
        <td><input class="code" value="${data.code||""}"></td>
        <td><input class="desc" value="${data.desc||""}"></td>
        <td><input class="uom" value="${data.uom||""}"></td>
        <td><input class="ordered" type="number" value="${data.ordered||0}"></td>
        <td><input class="received" type="number" value="${data.received||0}"></td>
        <td><input class="accepted" type="number" value="${data.accepted||0}"></td>
        <td><input class="rejected" type="number" value="${data.rejected||0}"></td>
        <td><input class="price" type="number" step="0.01" value="${data.price||0}"></td>
        <td class="total" data-value="0.00">0.00</td>
        <td><input class="remarks" value="${data.remarks||""}"></td>
        <td class="no-print"><button class="del warn">X</button></td>
      `;
                tr.querySelector(".del").onclick = () => {
                    tr.remove();
                    recalc();
                    showStatus("Row deleted", "success");
                };
                tr.querySelectorAll("input").forEach(i => {
                    i.oninput = () => {
                        recalc();
                        syncCell(i);
                    };
                    syncCell(i);
                });
                tbody.appendChild(tr);
                recalc();
            }

            function recalc() {
                const rows = tbody.querySelectorAll("tr");
                totalItems.textContent = rows.length;
                let rec = 0,
                    acc = 0,
                    gTotal = 0;

                rows.forEach(r => {
                    const received = +r.querySelector(".received").value || 0;
                    const accepted = +r.querySelector(".accepted").value || 0;
                    const price = +r.querySelector(".price").value || 0;
                    rec += received;
                    acc += accepted;

                    const total = accepted * price;
                    const totalCell = r.querySelector(".total");
                    totalCell.textContent = total.toFixed(2);
                    totalCell.setAttribute("data-value", total.toFixed(2));
                    gTotal += total;
                });

                totalReceived.textContent = rec;
                totalAccepted.textContent = acc;
                grandTotalCell.textContent = gTotal.toFixed(2);
                grandTotalCell.setAttribute("data-value", gTotal.toFixed(2));
            }

            function exportCSV() {
                const rows = [
                    ["Item Code", "Description", "UOM", "Qty Ordered", "Qty Received", "Qty Accepted", "Qty Rejected",
                        "Unit Price", "Total Price", "Remarks"
                    ]
                ];
                tbody.querySelectorAll("tr").forEach(r => {
                    rows.push([
                        r.querySelector(".code").value,
                        r.querySelector(".desc").value,
                        r.querySelector(".uom").value,
                        r.querySelector(".ordered").value,
                        r.querySelector(".received").value,
                        r.querySelector(".accepted").value,
                        r.querySelector(".rejected").value,
                        r.querySelector(".price").value,
                        r.querySelector(".total").textContent,
                        r.querySelector(".remarks").value
                    ]);
                });
                const csv = rows.map(r => r.join(",")).join("\n");
                const blob = new Blob([csv], {
                    type: "text/csv"
                });
                const url = URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = "grn.csv";
                a.click();
                URL.revokeObjectURL(url);
                showStatus("CSV exported successfully", "success");
            }

            function saveLocal() {
                const data = {
                    grnNo: document.getElementById("grnNo").value,
                    grnDate: document.getElementById("grnDate").value,
                    supplier: document.getElementById("supplier").value,
                    poNo: document.getElementById("poNo").value,
                    invNo: document.getElementById("invNo").value,
                    department: document.getElementById("department").value,
                    preparedBy: document.getElementById("preparedBy").value,
                    checkedBy: document.getElementById("checkedBy").value,
                    generalRemarks: document.getElementById("generalRemarks").value,
                    items: []
                };

                tbody.querySelectorAll("tr").forEach(r => {
                    data.items.push({
                        code: r.querySelector(".code").value,
                        desc: r.querySelector(".desc").value,
                        uom: r.querySelector(".uom").value,
                        ordered: r.querySelector(".ordered").value,
                        received: r.querySelector(".received").value,
                        accepted: r.querySelector(".accepted").value,
                        rejected: r.querySelector(".rejected").value,
                        price: r.querySelector(".price").value,
                        remarks: r.querySelector(".remarks").value
                    });
                });

                localStorage.setItem("grnData", JSON.stringify(data));
                showStatus("Data saved locally!", "success");
            }

            function loadLocal() {
                const data = JSON.parse(localStorage.getItem("grnData") || "{}");

                if (Object.keys(data).length === 0) {
                    showStatus("No saved data found!", "error");
                    return;
                }

                document.getElementById("grnNo").value = data.grnNo || "";
                document.getElementById("grnDate").value = data.grnDate || "";
                document.getElementById("supplier").value = data.supplier || "";
                document.getElementById("poNo").value = data.poNo || "";
                document.getElementById("invNo").value = data.invNo || "";
                document.getElementById("department").value = data.department || "Grocery";
                document.getElementById("preparedBy").value = data.preparedBy || "";
                document.getElementById("checkedBy").value = data.checkedBy || "";
                document.getElementById("generalRemarks").value = data.generalRemarks || "";

                tbody.innerHTML = "";
                if (data.items && data.items.length > 0) {
                    data.items.forEach(item => addRow(item));
                } else {
                    addRow();
                }

                showStatus("Data loaded from local storage!", "success");
            }

            function resetAll() {
                if (confirm("Are you sure you want to reset the entire form? All data will be lost.")) {
                    tbody.innerHTML = "";
                    document.getElementById("grnNo").value = generateGRN();
                    document.getElementById("grnDate").value = new Date().toISOString().split("T")[0];
                    document.getElementById("supplier").value = "";
                    document.getElementById("poNo").value = "";
                    document.getElementById("invNo").value = "";
                    document.getElementById("department").value = "Grocery";
                    document.getElementById("preparedBy").value = "";
                    document.getElementById("checkedBy").value = "";
                    document.getElementById("generalRemarks").value = "";
                    addRow();
                    showStatus("Form reset successfully!", "success");
                }
            }

            function loadSample() {
                tbody.innerHTML = "";
                addRow({
                    code: "1001",
                    desc: "Nestlé Milk Powder 1kg",
                    uom: "pcs",
                    ordered: 50,
                    received: 50,
                    accepted: 48,
                    rejected: 2,
                    price: 5.50,
                    remarks: "2 damaged"
                });
                addRow({
                    code: "2005",
                    desc: "Sunlight Soap 100g",
                    uom: "pcs",
                    ordered: 100,
                    received: 100,
                    accepted: 100,
                    rejected: 0,
                    price: 0.80
                });
                showStatus("Sample data loaded!", "success");
            }

            function showStatus(message, type) {
                pdfStatus.textContent = message;
                pdfStatus.className = `pdf-status ${type}`;
                pdfStatus.style.display = 'block';
                setTimeout(() => {
                    pdfStatus.style.display = 'none';
                }, 3000);
            }

            // PDF Export Function
            function exportToPDF() {
                showStatus("Generating PDF...", "success");

                // Temporarily hide buttons for PDF capture
                const buttons = document.querySelectorAll('.no-print');
                buttons.forEach(btn => btn.style.display = 'none');

                // Use html2canvas to capture the form as an image
                html2canvas(document.querySelector('.card'), {
                    scale: 2, // Higher quality
                    useCORS: true,
                    logging: false,
                    backgroundColor: '#ffffff'
                }).then(canvas => {
                    // Restore buttons
                    buttons.forEach(btn => btn.style.display = '');

                    const imgData = canvas.toDataURL('image/png');
                    const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
                    const imgWidth = 210; // A4 width in mm
                    const pageHeight = 295; // A4 height in mm
                    const imgHeight = canvas.height * imgWidth / canvas.width;
                    let heightLeft = imgHeight;
                    let position = 0;

                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;

                    // Add new pages if content is longer than one page
                    while (heightLeft >= 0) {
                        position = heightLeft - imgHeight;
                        pdf.addPage();
                        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }

                    // Save the PDF
                    const grnNo = document.getElementById("grnNo").value || "GRN";
                    pdf.save(`${grnNo}.pdf`);
                    showStatus("PDF saved successfully!", "success");
                }).catch(error => {
                    console.error('Error generating PDF:', error);
                    // Restore buttons in case of error
                    buttons.forEach(btn => btn.style.display = '');
                    showStatus("Error generating PDF. Please try again.", "error");
                });
            }

            // Event Listeners
            document.getElementById("addRow").onclick = () => addRow();
            document.getElementById("clearRows").onclick = () => {
                if (confirm("Clear all items?")) {
                    tbody.innerHTML = "";
                    recalc();
                    showStatus("All items cleared", "success");
                }
            };
            document.getElementById("exportCSV").onclick = exportCSV;
            document.getElementById("saveLocal").onclick = saveLocal;
            document.getElementById("loadLocal").onclick = loadLocal;
            document.getElementById("resetAll").onclick = resetAll;
            document.getElementById("loadSample").onclick = loadSample;
            document.getElementById("printBtn").onclick = exportToPDF;

            // Initialize form
            window.onload = () => {
                document.getElementById("grnNo").value = generateGRN();
                document.getElementById("grnDate").value = new Date().toISOString().split("T")[0];
                addRow();
            };
        </script>
    </body>

    </html>
@endsection
