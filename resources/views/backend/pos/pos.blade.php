 <!-- Content Wrapper. Contains page content -->
 @extends('layouts.pos.master')
 @section('link')
 
 @endsection
 @section('content')
    <!-- Content Header (Page header) -->
    
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card ">
            <div class="card-body">
               <div class="container">
                   <div class="row">
                    {{-- devide product list --}}
                    
                    <div class="col-7">
                      <input class="form-control d-none" type="text" id="date">
                    <div class="row">
                      <div  class="col-6 pl-0">
                        <div class="form-group">
                          <select class="form-control" name="customer" id="customer"></select>
                        </div>
                      </div>
                      <div style="" class="col-6 p-0">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-barcode"></i></div>
                          </div>
                          <input type="text" class="form-control" id="barcode" placeholder="Barcode">
                        </div>

                      </div>
                      <div class="col-12 p-0">
                        <div class="input-group mb-3">
                          <select class="form-control" name="" id="warehouse"></select>
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
                    {{-- <button class="btn btn-primary btn-sm" onclick="addNew()">Add <i class="fas fa-plus"></i></button> --}}
                  </div>
                
                   <div class="row mt-3">
                    <div class="col-12 col-md-12">
                        <table width="100%">
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
                            <tr>
                              <th width="50%">SMS: </th>
                              <td><input type="checkbox" checked ></td>
                            </tr>
                        </table>
                    </div>
                  </div>
                  <div class="float-right mt-4">
                    <button class="btn btn-secondary" onclick="Clean()">Reset</button>
                    <button class="btn btn-primary submit" onclick="formRequestTry()">Save</button>
                  </div>
                </div>
                <div class="col-md-5 ">
                  <div class="product-list border" style="min-height: 400px;">
                    <div class="container">
                      <div class="row">
                      <div class="col-md-6">
                        <div class="form-group mt-2">
                          <select class="form-control" name="" id="category" onchange="showProduct()"></select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group mt-2">
                          <select class="form-control" name="" id="brand"></select>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <input class="form-control form-control-sm" type="text" id="search" placeholder="Search">
                      </div>
                    </div>
                    </div>
                    <div class="row show-product" style="margin:10px">
                        
                        
                    </div>
                  </div>
                </div>
               </div>
            </div>
          </div>
         
      </div><!-- /.container-fluid -->
      <audio id="audio" src="{{asset('storage/audio/beep.wav')}}" autoplay="false"></audio>
      <audio id="audio2" src="{{asset('storage/audio/beep2.mp3')}}" autoplay="false"></audio>
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
  
  @include('backend.pos.internal-assets.js.script')
  @endsection