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
    /* .table_text{
      font-size: 20px !important;
    } */
  </style>
 @endsection
 @section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Chart Of Accounts</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Trial Balance</li>
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
                  <div class="card-title">Chart Of Account</div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12 col-md-5 ">
                </div>
                <div class="col-12 col-md-6">
                  <br>
                   <button class="btn btn-warning mt-2 float-right" onclick="Print()">Print</button>
                 </div>
              </div>
              {{-- Start Report --}}
              <br><br>
              @php
                $info=App\Models\CompanyInformations::first();
              @endphp
              <div class="report " style="margin-left:80px;margin-right:80px;">
                <div class="row">
                    <div class="col-6">
                        @include('layouts.adress')
                    </div>
                    <div class="col-6">
                        <div class="h2 mb-4 float-right font-weight-bold">Chart Of Account</div>
                    </div>
                    
                </div>
                <div style="line-height: 0;" class="text-right d-none inv_ledger">
                  <small><strong>Print Time :</strong> </small><small  id="print_time"></small>
                </div>
                    <table class="table_text table table-sm text-center  table-striped mt-4">
                        <thead>
                        <tr style="color:black;" class="">
                            <th class="text-left pl-2"  style="border:1px solid black;" width="20%">Class Name</th>
                            <th class="text-left pl-2"  style="border:1px solid black;" width="50%">Account Group</th>
                            <th class="text-left pl-2"  style="border:1px solid black;" width="50%">Account Name</th>
                            <th width="15%" style="border:1px solid black;">Balance</th>
                        </tr>
                        </thead>
                        <tbody id='data-load'>
                        </tbody>
                        <tfoot>
                          {{-- <tr style="color:black;" class="">
                            <th colspan='3'>Total</th>
                            <th class="text-right" id="blcount">0.00</th>
                          </tr> --}}
                        </tfoot>
                    </table>
                    <div class="text-right mt-5 d-none inv_ledger"> <small>Printed By <strong id="printed_user"></strong></small></div>
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
  @include('backend.reports.chart_of_account.internal-assets.js.script')
    <script>
      function  Print(){
        $('.report').printThis({
          importCSS: true,
          importStyle: true,
        });
      }
    </script>
  @endsection