 <!-- Content Wrapper. Contains page content -->
 @extends('layouts.master')
 @section('link')
  <style>
    #ammount{
      /* border:2px solid red; */
      background-color:#f4c2c2;
      font-weight: bold;
    }
  </style>
 @endsection
 @section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Manage Invoice</h1>
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
            <div class="card-body">
               <div class="container">
                   <div class="row">
                       <div class="col-12 col-md-2 ">
                        <select name="" id="sale_type" class="form-control" onchange="customerVisibility()">
                          <option value="0">Cash</option>
                          <option selected value="1">Regular</option>
                          <option value="2">Condition</option>
                        </select>
                       </div>
                       <div class="col-12 col-md-3 invisible" id="init-customer">
                          <div class="input-group">
                              <select class="form-control" id="customer" onchange="balance(this)">
                              </select>
                              <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="button" data-toggle="modal" id="customerModal" data-target='#modal'>Add <i class="fas fa-plus"></i></button>
                              </div>
                              <div class="invalid-feedback" id='customer_msg'></div>
                          </div>
                          <div class="total_balance d-none">Balance: <span id="customer-balance"></span></div>
                       </div>
                       <div class="col-12 col-md-2">
                          <div class="input-group">
                              <select class="form-control" id="market">
                              </select>
                              <div class="invalid-feedback" id='market_msg'></div>
                          </div>
                          <div class="total_balance d-none">Balance: <span id="customer-balance"></span></div>
                      </div>
                       <div class="col-12 col-md-1">
                        <div class="form-group">
                          <input type="text" class="form-control" id="hand_bill" placeholder="Hand Bill">
                        </div>
                       </div>
                       <div class="col-12 col-md-2 ">
                        <div class="form-group">
                          <select name="warehouse" id="warehouse" class="form-control"></select>
                          <div class="invalid-feedback" id="warehouse_msg"></div>
                        </div>
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
                   <div class="row">
                    
                    <div class="col-12 col-md-6 ">
                      <div class="row">
                        <div class="col-12 col-md-4">
                          <div class="form-group">
                            <label for="">Item Name</label>
                            <select tabindex='1' class="form-control" name="" id="product"></select>
                          </div>
                        </div>
                        <div class="col-12 col-md-4">
                          <div class="form-group">
                            <label for="">Brand/Company</label>
                            <select tabindex='2' class="form-control" name="" id="brand" onfocus="select2Open()"></select>
                          </div>
                        </div>
                        <div class="col-12 col-md-4">
                          <div class="form-group">
                            <label for="">Model</label>
                            <select tabindex='3' class="form-control" name="" id="model"></select>
                          </div>
                        </div>
                        
                        <div class="col-12 col-md-4">
                          <div class="form-group">
                            <label for="">Part ID</label>
                            <select tabindex='4' class="form-control" name="" id="part_id"></select>
                          </div>
                        </div>
                        <div class="col-12 col-md-3">
                          <div class="form-group">
                            <label for="">Quantity</label>
                            <input tabindex='5' type="number" class="form-control" id="quantity" placeholder="0.00" value="1">
                          </div>
                        </div>
                        <div class="col-12 col-md-3">
                          <div class="form-group">
                            <label for="">B.Rate</label>
                            <input tabindex='6' type="number" class="form-control" id="b_rate" placeholder="0.00">
                          </div>
                        </div>
                        <div class="col-12 col-md-2">
                          <div class="form-group">
                            <label for="">MLTP</label>
                            <input tabindex='7' type="number" class="form-control" id="mltp" placeholder="0.00">
                          </div>
                        </div>
                        <div class="col-12 col-md-3">
                          <div class="form-group">
                            <label for="">Amount</label>
                            <input  type="number" class="form-control" id="amount" placeholder="0.00" disabled>
                          </div>
                        </div>
                        <div class="col-12 col-md-3">
                          <div class="form-group">
                            <label for="">Total</label>
                            <input  type="number" class="form-control" id="total-amt" placeholder="0.00" disabled>
                          </div>
                        </div>
                        <div class="col-12 col-md-3">
                          <div class="form-group">
                            <label for="">Stock</label>
                            <input disabled type="number" class="form-control" id="stock" placeholder="0.00">
                          </div>
                        </div>
                      </div>
                     </div>
                     <div class="col-12 col-md-6 border ">
                      <div class="table-responsive">
                          <table class="table table-sm text-center table-bordered mt-2" id="add_product">
                              <thead>
                                  <tr>
                                    <th width='30%'>Product</th>
                                    <th width='15%'>Quantity</th>
                                    <th width='20%'>Price</th>
                                    <th width='25%'>Total</th>
                                    <th width='10%'>Action</th>
                                  </tr>
                              </thead>
                              <tbody id="item_table_body">

                              </tbody>
                          </table>
                      </div>
                   </div>
                   </div>
                   
                   
                   
                   <div class="col-12 col-md-1">
                    <button tabindex='8' class="btn btn-primary btn-sm" onclick="addNew()">Add <i class="fas fa-plus"></i></button>
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
                            <tr class="d-none">
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
                            <tr class="due d-none">
                              <th width="50%">Previous Due: </th>
                              <td>
                                <input disabled type="number" class="form-control form-control-sm" id="previous_due" placeholder="0.00">
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
                              <th width="50%">Paid Amount: </th>
                              <td><input type="number" class="form-control form-control-sm" id="ammount" placeholder="0.00"></td>
                            </tr>
                            <tr class="due d-none">
                              <th width="50%">Current Due: </th>
                              <td>
                                <input disabled type="number" class="form-control form-control-sm" id="current_due" placeholder="0.00">
                                <div class="invalid-feedback" id='total_payable_msg'></div>
                              </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="float-right mt-2">
                    <button class="btn btn-secondary" onclick="Clean()">Reset</button>
                    <button class="btn btn-primary submit" onclick="formRequestTry()">Submit</button>
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
              <h5 class="modal-title" id="exampleModalLabel">Add New Customer</h5>
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
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="customerFormRequest()">Save</button>
            </div>
          </div>
        </div>
      </div>
      {{-- endmodal --}}
    </section>
  @endsection

  @section('script')
  
  @include('backend.invoice.internal-assets.js.script')
  @endsection