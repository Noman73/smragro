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
            <h1 class="m-0">Company Info</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Setting</li>
              <li class="breadcrumb-item active">Company Info</li>
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
                <div class="col-12">
                  <div class="card-title">Company Info </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <form action="">
                <div class="form-group">
                  <label for="">Bin No</label>
                  <input type="text" class="form-control" id="bin_no" placeholder="Enter Bin no" value="{{isset($info->bin_no) ? $info->bin_no : ''}}">
               </div>
                 <div class="form-group">
                    <label for="">Company Name</label>
                    <input type="text" class="form-control" id="company_name" placeholder="Enter Company Name" value="{{isset($info->company_name) ? $info->company_name : ''}}">
                 </div>
                 <div class="form-group">
                    <label for="">Company Slogan</label>
                    <input type="text" class="form-control" id="company_slogan" placeholder="Enter Company Slogan" value="{{isset($info->company_slogan) ? $info->company_slogan : ''}}">
                 </div>
                 <div class="form-group">
                    <label for="">Adress</label>
                    <input type="text" class="form-control" id="adress" placeholder="Enter Company Adress" value="{{isset($info->adress) ? $info->adress : ''}}">
                 </div>
                 <div class="form-group">
                    <label for="">phone</label>
                    <input type="number" class="form-control" id="phone" placeholder="Enter Phone Number" value="{{isset($info->phone) ? $info->phone : ''}}">
                 </div>
                 <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter Email" value="{{isset($info->email) ? $info->email : ''}}">
                 </div>
                 <div class="form-group">
                  <label for="">Web</label>
                  <input type="text" class="form-control" id="web" placeholder="Enter Website URL" value="{{isset($info->web) ? $info->web : ''}}">
               </div>
                 <div class="form-group">
                    <label for="">Logo</label>
                    <input type="file" class="form-control" id="logo">
                 </div>
                 <div class="form-group">
                  <label for="">Icon</label>
                  <input type="file" class="form-control" id="icon">
               </div>
              </form>
              <button class="btn btn-primary float-right" onclick="formRequest()">Save</button>
            </div>
          </div>
      </div><!-- /.container-fluid -->
      {{-- endmodal --}}
    </section>
  @endsection

  @section('script')
  <script src="{{asset('storage/adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.0/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  @include('backend.setting.general_info.internal-assets.js.script')
  @endsection