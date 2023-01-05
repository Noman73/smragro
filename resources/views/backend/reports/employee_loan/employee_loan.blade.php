 <!-- Content Wrapper. Contains page content -->
 @extends('layouts.master')
 @section('link')
 <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <style>
    .report td{
      border-top:1px solid black !important;
      border-bottom:1px solid black !important;
      /* color:red; */
    }
  </style>
 @endsection
 @section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Employee Loan List</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Employee List</li>
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
                  <div class="card-title">Employee Loan Report</div>
                </div>
              </div>
            </div>
            <div class="card-body">
              
              {{-- Start Report --}}
              <br><br>
              @php
                $info=App\Models\CompanyInformations::first();
              @endphp
              <button style="margin-right:80px;" class="d-block btn btn-warning float-right" onclick="Print()">Print</button><br><br>
              <div class="report " style="margin-left:80px;margin-right:80px;">
                
                <div class="row">
                    <div class="col-6">
                        <img  src="{{asset('storage/logo/'.$info->logo)}}" alt="Logo">
                    </div>
                    <div class="col-6">
                        <div class="h2 mb-4 float-right font-weight-bold">Employee Loan</div>
                        
                    </div>
                    
                    <div class="col-5 mt-1">
                        {{$info->adress}}<br>
                        {{$info->phone}}<br>
                        {{$info->email}}<br>
                        {{$info->web}}<br>
                        Bin No: {{$info->bin_no}}<br>
                    </div>
                </div>
                    <small style="line-height: 0;" class="text-right d-block">Print Time <span id="print-time"></span></small>
                    <table style="border:1px solid black !important;" class="table table-sm text-center  table-striped mt-4">
                        <thead>
                        <tr style="color:black;" class="">
                            <th width="30%">Name</th>
                            <th width="30%">Adress</th>
                            <th width="20%">Mobile</th>
                            <th width="20%">C. Balance</th>
                        </tr>
                        </thead>
                        <tbody id='data-load'>
                        </tbody>
                    </table>
              </div>
              
              {{-- End Report --}}
            </div>
          </div>
      </div>
      {{-- modal --}}
  
      {{-- endmodal --}}
    </section>
  @endsection
  @section('script')
  <script src="{{asset('storage/adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.0/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js" integrity="sha512-d5Jr3NflEZmFDdFHZtxeJtBzk0eB+kkRXWFQqEc1EKmolXjHm2IKCA7kTvXBNjIYzjXfD5XzIjaaErpkZHCkBg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  @include('backend.reports.employee_loan.internal-assets.js.script')
    <script>
      function  Print(){

        $('.report').printThis({
          importCSS: true,
          importStyle: true,

        });
      }
    </script>
  @endsection