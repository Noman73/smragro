 <!-- Content Wrapper. Contains page content -->
 @extends('layouts.master')
 @section('link')
 <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
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
            <h1 class="m-0">Fund Transfer</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Fund Transfer</li>
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
            <div class="card-header bg-dark">
              <div class="row">
                <div class="col-6">
                  <div class="card-title">Fund Transfer </div>
                </div>
                <div class="col-6">
                  <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal" data-whatever="@mdo">Add New</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-sm text-center table-bordered" id="datatable">
                <thead>
                  <tr>
                    <th>SL</th>
                    <th>Date</th>
                    <th>TRX-ID</th>
                    <th>Amount</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                
              </table>
            </div>
          </div>
      </div>
      {{-- modal --}}
      <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Fund Transfer</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body ml-5 mr-5">
              <form>
                <input type="hidden" id="id">
                <div class="row">
                    <div class="col-12 col-md-3 m-auto m-0 ">
                        <div class="form-group">
                        <label for="" class="text-center d-block">Date</label>
                        <input type="text" class="font-weight-bold form-control text-center" id="date">
                        <div class="invalid-feedback" id="date_msg"></div>

                        </div>
                    </div>
                    
                  </div>
            <div class="row">
              <div class="col-12 col-md-6">
                <h4>From</h4><hr>
                <div class="row">
                    <div class="col-12 col-md-12 ">
                        <div class="form-group">
                        <label for="">Method</label>
                        <select name="" id="from_method" class="form-control" onchange="paymentMethod(this);getBalance(this.value);">
                            <option value="">--select--</option>
                            <option value="0">Cash</option>
                            <option value="1">Bank</option>
                        </select>
                        <div class="invalid-feedback" id="from_method_msg"></div>
                        </div>
                    </div>
                  <div class="col-12 col-md-12 from_bank">
                    <div class="form-group">
                      <label for="">Bank</label>
                      <select name="" id="from_bank" class="form-control" onchange="getBalance(1,this.value)">
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <h4>To</h4><hr>
                <div class="row">
                    <div class="col-12 col-md-12">
                      <div class="form-group">
                        <label for="">Method</label>
                        <select name="" id="to_method" class="form-control" onchange="paymentMethod(this);getBalance(this.value);">
                          <option value="">--select--</option>
                          <option value="0">Cash</option>
                          <option value="1">Bank</option>
                        </select>
                        <div class="invalid-feedback" id="to_method_msg"></div>
                      </div>
                    </div>
                  <div class="col-12 col-md-12 to_bank">
                    <div class="form-group">
                      <label for="">Bank</label>
                      <select name="" id="to_bank" class="form-control" onchange="getBalance(1,this.value)">
                      </select>
                      <div class="invalid-feedback" id="to_bank_msg"></div>
                    </div>
                  </div>
                  
                </div>
              </div>
              </div>
                {{--  --}}
                <div class="row">
                  <div class="col-12 col-md-12">
                    <div class="form-group">
                      <label for="" class="">Note:</label>
                      <input name="note" id="note" class="form-control" placeholder="Note">
                      <div class="invalid-feedback" id="note_msg"></div>
                    </div>
                  </div>
                <div class="col-12 col-md-3 m-auto m-0">
                  <div class="form-group">
                    <label for="" class="text-center d-block">Amount</label>
                    <input name="ammount" id="ammount" class="form-control" placeholder="0.00">
                    <div class="invalid-feedback" id="ammount_msg"></div>
                  </div>
                </div>
                
              </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary submit" onclick="formRequestTry()">Save</button>
            </div>
          </div>
        </div>
      </div>
      {{-- endmodal --}}
    </section>
  @endsection

  @section('script')
  <script src="{{asset('storage/adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.0/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  @include('backend.fund_transfer.internal-assets.js.script')
  @endsection