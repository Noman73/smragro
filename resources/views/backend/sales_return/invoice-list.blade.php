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
            <h1 class="m-0">Sales Return Invoice List</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Sales Return</li>
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
                  <div class="card-title">Sales Return Invoice </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-sm text-center table-bordered" id="datatable">
                <thead>
                  <tr>
                    <th>SL.</th>
                    <th>Date</th>
                    <th>ID</th>
                    <th>Hand Bill</th>
                    <th>Total Ammount</th>
                    <th>Customer Name</th>
                    <th>Invoice Type</th>
                    <th>User</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
      </div><!-- /.container-fluid -->
    </section>
  @endsection

  @section('script')
  <script src="{{asset('storage/adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.0/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  @include('backend.sales_return.internal-assets.js.invoice_list')
  @endsection