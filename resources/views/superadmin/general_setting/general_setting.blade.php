 <!-- Content Wrapper. Contains page content -->
 @extends('layouts.superadmin.master')
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
            <h1 class="m-0">General Setting</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">General Setting</li>
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
                  <div class="card-title">Setting </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <form action="">
                <div class="form-group">
                  <label for="" class="ml-3">Warehouse</label>
                  <input type="checkbox" >
                  <label for="" class="ml-3">Discount</label>
                  <input type="checkbox" >
                  <label for="" class="ml-3">Vat</label>
                  <input type="checkbox">
                </div>
                <button class="btn btn-primary">Save</button>
              </form>
            </div>
          </div>
      </div><!-- /.container-fluid -->

    </section>
  @endsection

  @section('script')
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.0/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  @include('backend.category.internal-assets.js.script')
  @endsection