@extends('layouts.master') @section('title')
    <h3 class="mb-0">Sales System</h3>
    @endsection @section('breadcumb')
    @parent <li class="breadcrumb-item active" aria-current="page">Sales</li>
    @endsection @section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="row"> <!-- Left Panel: Customer & Product Entry -->
                <div class="col-md-8">
                    <div class="card mb-4 p-3">
                        <div class="row mb-2">
                            <div class="col-md-6"> <label>Customer Name</label> <input type="text" class="form-control"
                                    value="Walk-In Customer"> </div>
                            <div class="col-md-6"> <label>Customer ID</label>
                                <div class="input-group"> <input type="text" class="form-control"> <button
                                        class="btn btn-primary"><i class="fas fa-search"></i></button> <button
                                        class="btn btn-success">+</button> </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6"> <label>Address</label> <input type="text" class="form-control"
                                    value="Udabadda Ella, Panwwewa, Maliththa"> </div>
                            <div class="col-md-6"> <label>Contact No</label> <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6"> <label>Product Code</label>
                                <div class="input-group"> <input type="text" class="form-control"> <button
                                        class="btn btn-primary"><i class="fas fa-search"></i></button> <button
                                        class="btn btn-success">Other</button> </div>
                            </div>
                            <div class="col-md-6"> <label>Quantity</label>
                                <div class="input-group"> <input type="number" class="form-control"> <button
                                        class="btn btn-primary">+</button> <button class="btn btn-info">Rs / %</button>
                                </div>
                            </div>
                        </div> <!-- Products Table -->
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Product Name</th>
                                        <th>S.P</th>
                                        <th>Dis.P</th>
                                        <th>Qty</th>
                                        <th>SubTotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>T200 25KG</td>
                                        <td>120.00</td>
                                        <td>120.00</td>
                                        <td>5.00</td>
                                        <td>600.00</td>
                                        <td><button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>T200 50KG</td>
                                        <td>7,250.00</td>
                                        <td>7,000.00</td>
                                        <td>6.00</td>
                                        <td>42,000.00</td>
                                        <td><button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <div>Total Items: <strong>2</strong></div>
                            <div> Total Amount: <strong>42600.00</strong><br> Discount: <strong>1500.00</strong> </div>
                        </div>
                    </div>
                </div> <!-- Right Panel: Payment -->
                <div class="col-md-4">
                    <div class="card mb-4 p-3">
                        <div class="mb-2"> <label>Invoice No:</label> <span class="badge bg-warning">IN00000502</span>
                        </div>
                        <div class="mb-2"> <label>Cheque Details</label> <input type="text" class="form-control mb-1"
                                placeholder="Chq #"> <input type="date" class="form-control mb-1"
                                value="{{ date('Y-m-d') }}"> <input type="text" class="form-control mb-1"
                                placeholder="Amount"> </div>
                        <div class="mb-2"> <label>Card Details</label> <input type="text" class="form-control mb-1"
                                placeholder="Card No"> <input type="text" class="form-control mb-1"
                                placeholder="Cred. Amt"> <input type="text" class="form-control mb-1"
                                placeholder="Cred. Amt"> </div>
                        <div class="mb-2"> <label>Advance</label> <input type="text" class="form-control"
                                value="0.00"> </div>
                        <div class="mb-2"> <label>Cash</label> <input type="text" class="form-control" value="0.00">
                        </div>
                        <div class="mb-2"> <label>Balance</label> <input type="text" class="form-control text-danger"
                                value="-42600.0"> </div>
                        <div class="d-grid gap-2"> <button class="btn btn-primary">Save</button> <button
                                class="btn btn-secondary">Find</button> <button class="btn btn-info">Adv</button> <button
                                class="btn btn-warning">Ret</button> <button class="btn btn-danger">Cancel</button> <button
                                class="btn btn-success">Print</button> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
