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
            <h1 class="m-0">Product Price</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Payment</li>
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
                  <div class="card-title">Product Price Set</div>
                </div>
                <div class="col-6">
                  <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal" data-whatever="@mdo">Add New</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12 col-md-3 ">
                  <div class="form-group">
                    <label for="">Customer</label>
                    <select name="" id="search_customer"  class="form-control customer">
                    </select>
                  </div>
                </div>
                <div class="col-12 col-md-3 ">
                  <div class="form-group">
                    <label for="">Category</label>
                    <select name="" id="category" class="form-control category">
                    </select>
                  </div>
                </div>
                <div class="col-12 col-md-3">
                 <br>
                  <button class="btn btn-primary mt-2 float-left" onclick="Apply()">Apply</button>
                </div>
              </div>

              <table class="table table-sm text-center table-bordered">
                <thead>
                  <tr>
                    <th>Update Date</th>
                    <th>Code</th>
                    <th>Product</th>
                    <th>Set Price</th>
                    <th>Last Price</th>
                    <th>Default Price</th>
                  </tr>
                </thead>
                <tbody id='data-load'>
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
              <h5 class="modal-title" id="exampleModalLabel">Add New Payment</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <form>
                <input type="hidden" id="id">
                <div class="row">
                  <div class="col-12 col-md-6 ">
                    <div class="form-group">
                      <label for="">Customer</label>
                      <select name="customer" id="customer"  class="form-control customer" onchange="getSetPrice()">
                      </select>
                    </div>
                  </div>
                  <div class="col-12 col-md-6 ">
                    <button class="btn btn-sm btn-primary float-right  mb-1 " onclick="event.preventDefault();addItem()"><i class='fa fa-plus'></i> Add</button>
                  </div>
                </div>
                <table class="table table-sm text-center table-bordered">
                  <thead>
                    <tr>
                      <th width="50%">Product</th>
                      <th width="40%">Price</th>
                      <th width="10%">action</th>
                    </tr>
                  </thead>
                  <tbody id="payment-body">
                  </tbody>
                </table>
               
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="formRequest()">Save</button>
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
  @include('backend.make_price.internal-assets.js.script')
  @endsection