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
            <h1 class="m-0">Ledger Report</h1>
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
                  <div class="card-title">Ledger Report</div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12 col-md-3 ">
                  <div class="form-group">
                    <label for="">From Date</label>
                    <input type="text" id="fromDate" class="form-control">
                  </div>
                </div>
                <div class="col-12 col-md-3 ">
                  <div class="form-group">
                    <label for="">To Date</label>
                    <input type="text" id="toDate" class="form-control">
                  </div>
                </div>
                <div class="col-12 col-md-3 ">
                  <div class="form-group">
                    <label for="">Ledger</label>
                    <select name="" id="ledger"  class="form-control ledger">
                    </select>
                  </div>
                </div>
                <div class="col-12 col-md-3 ">
                  <div class="form-group">
                    <label for="">Sub Ledger</label>
                    <select name="" id="subledger" class="form-control subledger">
                    </select>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                 <br>
                  <button class="btn btn-primary mt-2 float-left" onclick="Apply()">Apply</button>
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
                        <img  src="{{asset('storage/logo/'.$info->logo)}}" alt="Logo">
                    </div>
                    <div class="col-6">
                        <div class="h2 mb-4 float-right font-weight-bold">Ledger</div>
                        
                    </div>
                    
                    <div class="col-5 mt-1">
                        {{$info->adress}}<br>
                        {{$info->phone}}<br>
                        {{$info->email}}<br>
                        {{$info->web}}<br>
                        Bin No: {{$info->bin_no}}<br>
                    </div>
                    <div class="col-7 mt-1 text-right d-none inv_ledger">
                          <label>Account Name</label> : <span id="inv_ledger"></span><br>
                          <label>From Date</label> : <span id="inv_from_date"></span><br>
                          <label>To Date</label> : <span id="inv_to_date"></span><br>
                    </div>
                </div>
                    <table class="table table-sm text-center  table-striped mt-4">
                        <thead>
                        <tr style="color:black;" class="">
                            <th width="10%">Txr. Date</th>
                            <th width="10%">Post Date</th>
                            <th width="20%">Particular</th>
                            <th width="15%">Instrument</th>
                            <th width="15%">Debit</th>
                            <th width="15%">Credit</th>
                            <th width="15%">Balance</th>
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
  @include('backend.reports.ledger.account_ledger.internal-assets.js.script')
    <script>
      function  Print(){

        $('.report').printThis({
          importCSS: true,
          importStyle: true,

        });
      }
    </script>
  @endsection