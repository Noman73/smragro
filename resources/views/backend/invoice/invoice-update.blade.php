 <!-- Content Wrapper. Contains page content -->


 @extends('layouts.master')
 @section('link')
 
 @endsection
 @section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            {{-- {{trim(json_encode($invoice),'[]')}} --}}
            <h1 class="m-0">Invoice Edit</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Invoice</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card ">
            {{-- <div class="card-header">
              <div class="row">
                <div class="col-6">
                  <div class="card-title">Supplier </div>
                </div>
                <div class="col-6">
                  <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal" data-whatever="@mdo">Add New</button>
                </div>
              </div>
            </div> --}}
            <div class="card-body">
               <div class="container">
                   <div class="row">
                       <div class="col-12 col-md-2 ">
                        <select name="" id="sale_type" class="form-control" onchange="customerVisibility()">
                          <option value="0">Cash</option>
                          <option value="1">Regular</option>
                          <option value="2">Condition</option>
                        </select>
                           {{-- <input type="radio" onchange="customerVisibility()"  name="sale_type[]" id="cash" value="0" checked>
                           <label for="walking">Cash</label>
                           <input type="radio" onchange="customerVisibility()" name="sale_type[]" id="regular" value="1">
                           <label for="regular">Regular</label> --}}
                           {{-- <input type="radio" onchange="customerVisibility()" name="sale_type[]" id="condition" value="2">
                           <label for="condition">Condition</label>
                           <div class="invalid-feedback" id='purchase_type_msg'></div> --}}
                       </div>
                       <div class="col-12 col-md-3 invisible" id="init-customer">
                          <div class="input-group">
                              <select class="form-control" id="customer" onchange="balance(this)">
                              </select>
                              <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="button" id="customerModal">Add <i class="fas fa-plus"></i></button>
                              </div>
                              <div class="invalid-feedback" id='customer_msg'></div>
                          </div>
                          <div class="total_balance d-none">Balance: <span id="customer-balance"></span></div>
                       </div>
                       <div class="col-12 col-md-2">
                        <div class="form-group">
                          <input type="text" class="form-control" id="hand_bill" placeholder="Hand Bill">
                        </div>
                       </div>
                       <div class="col-12 col-md-2 mt-2">
                         <div class="float-right d-none">

                            <input type="radio" name="action[]" value="0" checked>
                            <label for="">Sale</label>
                            <input type="radio" name="action[]" value="1">
                            <label for="">Return</label>
                            <div class="invalid-feedback" id='action_msg'></div>
                         </div>
                       </div>
                       <div class="col-12 col-md-2">
                         <div class="form-group">
                           <input type="text" class="form-control" id="date" placeholder="Date">
                         </div>
                       </div>

                       <div class="col-12 col-md-1">
                        <button class="btn btn-primary float-right" onclick="addNew()">Add <i class="fas fa-plus"></i></button>
                      </div>
                   </div>
                   <div class="row" id="w_customer">
                      <div class="col-12 col-md-3 mt-2">
                        <div class="form-group">
                           <label for="">Mobile</label>
                           <input type="number" class="form-control" id="w_mobile" placeholder="Enter Mobile">
                           <div class="invalid-feedback" id="mobile_msg"></div>
                        </div>
                      </div>
                      <div class="col-12 col-md-3 mt-2">
                        <div class="form-group">
                           <label for="">Name</label>
                           <input type="text" class="form-control" id="w_name" placeholder="Enter Name">
                           <div class="invalid-feedback" id="name_msg"></div>
                        </div>
                      </div>
                      <div class="col-12 col-md-6 mt-2">
                        <div class="form-group">
                           <label for="">Adress</label>
                           <input type="text" class="form-control" id="w_adress" placeholder="Enter adress">
                        </div>
                      </div>
                   </div>
                   <div class="table-responsive">
                       <table class="table table-sm text-center table-bordered" id="add_product">
                           <thead>
                               <tr>
                                   <th width='40%'>Product</th>
                                   <th width='15%'>Stock</th>
                                   <th width='10%'>Quantity</th>
                                   <th width='10%'>Price</th>
                                   <th width='15%'>Total</th>
                                   <th width='10%'>Action</th>
                               </tr>
                           </thead>
                           <tbody id="item_table_body">

                           </tbody>
                       </table>
                   </div>
                   
                   <div class="row mt-3">
                     {{-- note  --}}
                    <div class="col-12 col-md-8">
                      <div class="form-group">
                        <label for="">Note :</label>
                        <select class="form-control" name="" id="note"></select>
                      </div>
                      <div class="form-group">
                        <label for="">staff Note :</label>
                        <textarea class="form-control" name="" id="staff_note" rows="2" placeholder="write something..."></textarea>
                      </div>
                      <div>
                          <input type="radio" onchange="saleByCheck()"  name="sale_by[]" id="sale_by_self" value="0" checked>
                           <label for="cash">By Self</label>
                           <input type="radio" onchange="saleByCheck()" name="sale_by[]" id="sale_by_courier" value="1">
                           <label for="walking">By Courier</label>
                           <input type="radio" onchange="saleByCheck()" name="sale_by[]" id="sale_by_shipping" value="2">
                           <label for="walking">Shipping To</label>
                      </div>
                      <div class="d-none" id="courier-list">
                        <select class="form-control form-control-sm" name="" id="courier">
                        </select>
                      </div>
                      <div class="d-none shipping">
                        <div class="row mt-2">
                          <div class="col-3">
                            <div class="form-group">
                              <label for="">name:</label>
                              <input type="text" class="form-control" id="shipping_name" placeholder="Enter Name">
                            </div>
                          </div>
                          <div class="col-3">
                            <div class="form-group">
                              <label for="">mobile:</label>
                              <input type="number" class="form-control" id="shipping_mobile" placeholder="Enter Mobile">
                            </div>
                          </div>
                          <div class="col-3">
                            <div class="form-group">
                              <label for="">adress:</label>
                              <input type="text" class="form-control" id="shipping_adress" placeholder="Enter Adress">
                            </div>
                          </div>
                          <div class="col-3">
                            <div class="form-group">
                              <label for="">amount:</label>
                              <input type="text" class="form-control" id="condition_amount" placeholder="Enter Amount">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    {{-- end note --}}
                    <div class="col-12 col-md-4 ">
                        <table>
                            <tr>
                                <th width="50%">Total: </th>
                                <td>
                                  <input disabled type="number" class="form-control form-control-sm" id="total" placeholder="0.00" required>
                                  <div class="invalid-feedback" id='total_msg'></div>
                                </td>
                            </tr>
                            <tr class=''>
                                <th width="50%">Total Item: </th>
                                <td>
                                  <input disabled type="number" class="form-control form-control-sm" id="total-item" placeholder="0.00">
                                  <div class="invalid-feedback" id='total-item_msg'></div>
                                </td>
                            </tr>
                            <tr>
                              <th width="50%">Discount: </th>
                              <td>
                                <div class="input-group input-group-sm">
                                  <input type="number" class="form-control " id="discount" placeholder="0.00" aria-describedby="validationTooltipUsernamePrepend" required>
                                  <div class="input-group-append">
                                    <div class="input-group-text">
                                      % <input id="discountCheck" type="checkbox" class="ml-1">
                                    </div>
                                  </div>
                                </div>
                                {{-- <input type="number" class="form-control form-control-sm" id="vat" placeholder="0.00"> --}}
                              </td>
                          </tr>
                            <tr>
                                <th width="50%">VAT: </th>
                                <td>
                                  <input type="number" class="form-control form-control-sm" id="vat" placeholder="0.00">
                                  <div class="invalid-feedback" id='vat_msg'></div>
                                </td>
                            </tr>
                            <tr>
                                <th width="50%">Transport: </th>
                                <td><input type="number" class="form-control form-control-sm" id="transport" placeholder="0.00"></td>
                            </tr>
                            <tr>
                                <th width="50%">Total Payable: </th>
                                <td>
                                  <input disabled type="number" class="form-control form-control-sm" id="total_payable" placeholder="0.00">
                                  <div class="invalid-feedback" id='total_payable_msg'></div>
                                </td>
                            </tr>
                            {{-- payment  --}}
                            <tr id="payment_method_row">
                                <th width="50%">Payment Method: </th>
                                <td>
                                  <input type="radio" onchange="paymentMethod()"  name="payment_method_type[]" id="cash" value="0" checked>
                                  <label for="cash">Cash</label>
                                  <input type="radio" onchange="paymentMethod()" name="payment_method_type[]" id="regular" value="1">
                                  <label for="walking">Bank</label>
                                  <div class="invalid-feedback" id='purchase_type_msg'></div>
                                </td>
                            </tr>
                            <tr class="bank d-none">
                                <th width="50%">Bank: </th>
                                <td>
                                  <select class="form-control form-control-sm" name="" id="bank"></select>
                                  <div class="invalid-feedback" id="bank_msg"></div>
                                </td>
                            </tr>
                              <tr class="bank d-none">
                                  <th width="50%">Cheque No: </th>
                                  <td><input type="number" class="form-control form-control-sm" id="cheque_no" placeholder="0.00"></td>
                              </tr>
                              <tr class="bank d-none">
                                <th width="50%">Issue Date: </th>
                                <td><input type="text" class="form-control form-control-sm" id="cheque_issue_date" placeholder="dd-mm-yyyy"></td>
                              </tr>
                              <tr class="bank d-none">
                                  <th width="50%">Cheque Photo: </th>
                                  <td>
                                    <div class="custom-file">
                                      <input type="file" class="custom-file-input form-control-sm"  id="cheque_photo" required>
                                      <label class="custom-file-label" for="validatedCustomFile">photo</label>
                                    </div>
                                  </td>
                              </tr>
                            <tr>
                              <th width="50%">Ammount: </th>
                              <td><input type="number" class="form-control form-control-sm" id="ammount" placeholder="0.00"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="float-right mt-2">
                    <button class="btn btn-secondary" onclick="Clean()">Reset</button>
                    <button class="btn btn-primary" onclick="formRequestTry()">Save</button>
                </div>
               </div>
            </div>
          </div>
      </div><!-- /.container-fluid -->

      {{-- modal --}}
      <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add New Supplier</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <input type="hidden" id="id">
                <div class="row">
                  <div class="col-md-8 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="recipient-name" class="col-form-label">Name:</label>
                      <input type="text" class="form-control" id="name" placeholder="Enter Name">
                      <div class="invalid-feedback" id="name_msg">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="recipient-email" class="col-form-label">Email:</label>
                      <input type="email" class="form-control" id="email" placeholder="Enter Email">
                      <div class="invalid-feedback" id="email_msg">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 mr-auto ml-auto"> 
                    <div class="form-group">
                      <label for="message-text" class="col-form-label">Adress:</label>
                      <input type="text" class="form-control" id="adress" placeholder="Enter Adress">
                      <div class="invalid-feedback" id="adress_msg">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="message-text" class="col-form-label">Phone:</label>
                      <input type="number" class="form-control" id="phone" placeholder="Enter Phone Number">
                      <div class="invalid-feedback" id="phone_msg">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="opening_balance" class="font-weight-bold">Opening Balance:</label>
                      <div class='row'>
                        <div class='col-sm-9'>
                            <input class="form-control " id="opening_balance"  type="number" placeholder="Opening Balance">
                            <div id="opening_balance_msg" class="invalid-feedback">
                            </div>
                          </div>
                          <div class='col-sm-3'>
                            <select class="form-control" id="balance_type" >
                            <option value="1">Balance</option>
                            <option value="0">Due</option>
                            </select>
                            <div id="balance_type_msg" class="invalid-feedback">
                            </div>
                          </div>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-8 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="adress" class="font-weight-bold">Supplier Type:</label>
                      <select class="form-control " id="supplier_type">
                        <option value="">--select--</option>
                        <option value="Distributor">Distributor</option>
                        <option value="Whole Saler">Whole Saler</option>
                        <option value="Company">Company</option>
                      </select>
                      <div id="supplier_type_msg" class="invalid-feedback">
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="supplierFormRequest()">Save</button>
            </div>
          </div>
        </div>
      </div>
      {{-- endmodal --}}
    </section>
  @endsection

  @section('script')
  
  @include('backend.invoice.internal-assets.js.update-script')


  @endsection