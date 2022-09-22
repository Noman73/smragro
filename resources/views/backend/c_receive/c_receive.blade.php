 <!-- Content Wrapper. Contains page content -->
 @extends('layouts.master')
 @section('link')
 <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  
 @endsection
 @section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Customer Receive</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Customer Receive</li>
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
                  <div class="card-title">Customer Receive </div>
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
              <h5 class="modal-title" id="exampleModalLabel">Add New Customer Receive</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body mr-5 ml-5">

              <form>
                <div id="data_id">
                </div>
                <input type="hidden" id="id">
                <div class="row">
                  <div class="col-12 col-md-3 ">
                    <div class="form-group">
                      <label for="">Method</label>
                      <select name="" id="method" class="form-control" onchange="paymentMethod(this)">
                        <option value="0">Cash</option>
                        <option value="1">Bank</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-12 col-md-3 ">
                    <div class="form-group">
                      <label for="">Date</label>
                      <input type="text" class="form-control" id="date">
                    </div>
                  </div>
                  <div class="col-12 col-md-6 ">
                    {{-- <button class="btn btn-sm btn-primary float-right  mb-1 " onclick="event.preventDefault();addItem()"><i class='fa fa-plus'></i> Add</button> --}}
                  </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-3 bank">
                      <div class="form-group">
                        <label for="">Bank</label>
                        <select name="" id="bank" class="form-control">
                        </select>
                      </div>
                    </div>
                    <div class="col-12 col-md-3 bank" >
                      <div class="form-group">
                        <label for="">Cheque No</label>
                        <input name="" id="cheque_no" class="form-control">
                      </div>
                    </div>
                    <div class="col-12 col-md-3 bank">
                      <div class="form-group">
                        <label for="">Receive Date</label>
                        <input type="text" name="" id="issue_date" class="form-control">
                      </div>
                    </div>
                    <div class="col-12 col-md-3 bank">
                      <div class="form-group">
                        <label for="">Cheque Photo</label>
                        <input type="file" name="" id="cheque_photo" class="form-control">
                      </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-12 col-md-8">
                    <div class="form-group">
                      <label for="">Customer</label>
                      <select name="customer" id="customer" class="form-control"></select>
                    </div>
                  </div>
                  <div class="col-12 col-md-4">
                    <div class="form-group">
                      <label for="">Receive Amount</label>
                      <input name="ammount" id="ammount" class="form-control" placeholder="0.00">
                    </div>
                  </div>
                </div>
                <textarea name="note" id="note" cols="30" rows="2" class="form-control" placeholder="Write Note."></textarea>
               
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="formRequestTry()">Save</button>
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
  @include('backend.c_receive.internal-assets.js.script')
  @endsection