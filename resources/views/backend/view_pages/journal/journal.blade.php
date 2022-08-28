 <!-- Content Wrapper. Contains page content -->
 @extends('layouts.master')
 @section('link')

 
 <style>
 /* body{
  font-size: 12px;
} */
.invoice_title{
  font-size: 18px;
  font-weight: bold;
  text-decoration: underline;
  text-transform: uppercase;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  padding: 3px;
  line-height: 1.42857143;
  vertical-align: top;
  border-top: 1px solid #ddd;
}
.footer{
   text-align:center;
   margin-top:25px;
}

 </style>

 @endsection
 @section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Payment History</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Invoice</li>
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
            {{-- <div class="card-header">
              <div class="row">
                <div class="col-6">
                  <div class="card-title">Supplier </div>
                </div>
                <div class="col-6">
                  <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal" data-whatever="@mdo">Add New</button>
                </div>
              </div>
            </div> --}}
            <div class="card-body">
              @php 
              use Riskihajar\Terbilang\Facades\Terbilang;
              $info=App\Models\CompanyInformations::first();
              @endphp
              <div class="float-right clearfix">
                <a target='_blank' class="btn btn-warning" href='{{URL::to('admin/view-pages/journal-print/'.$journalInvoice->id)}}'>Print</a>
              </div>  
                  <div id="print" class="print mt-5" >
                    <div class="row invoice_header">
                      <div class="col-xs-5" style="width: 20%; float:left;">
                          <img src="{{asset('storage/logo/'.$info->logo)}}" width="100%" alt="Logo">
                      </div>
                      <div class="col-xs-7" style="width: 80%; text-align:right">
                          <div style="padding:5px; width:100%; text-align:right;">
                              <span style="font-size: 16px;">
                                  <b>
                                      Journal
                                  </b>
                              </span><br>
                              @php
                              
                              // dd($invoice);
                              @endphp
                              Receipt No :<b>J-{{date('dm',$journalInvoice->date).substr(date('Y',$journalInvoice->date),-2).$journalInvoice->id}}</b> <br>
                              Transaction Type :<b>Journal Voucer</b> <br>
                              Date : {{date('d-m-Y',intval($journalInvoice->date))}} 
                          </div>
                      </div>
                    </div>
                  {{-- <div class="row">
                      <div class="col-xs-6" style="width: 100%;float:left">
                          <br>
                          <table class="table table-bordered">
                            @foreach($invoice as $method)
                            @if($method->credit!=0)
                              Method : <b>{{$method->name}}</b> 
                              @if($method->sub_name!='')  
                              Name : <b>{{$method->sub_name}}</b> <br>
                              @endif
                              
                            @endif
                            @endforeach
                          </table>
                      </div>
                  </div> --}}
                  <div class="row">
                      <div class="col-md-12">
                          <div class="table-responsive">
                              <table class="table table-bordered table-striped">
                                  <thead>
                                      <tr>
                                          <th width="30%" class="text-left">Name</th>
                                          <th width="20%" class="text-left">Sub Name</th>
                                          <th width="15%" class="text-center">Comments</th>
                                          <th width="15%" class="text-right">Debit</th>
                                          <th width="15%" class="text-right">Credit</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    @php
                                    $i=0;
                                    $total=0;
                                    $total_debit=0;
                                    $total_credit=0;
                                    @endphp
                                    @foreach($invoice as $data)
                                      @php
                                      $total_debit+=floatval($data->debit);
                                      $total_credit+=floatval($data->credit);
                                      @endphp
                                          <tr>
                                              <td>{{$data->name}}</td>
                                              <td class="text-left">{{$data->sub_name}}</td>
                                              <td class="text-left">{{($data->comment)}}</td>
                                              <td class="text-right">৳{{$data->debit}}</td>
                                              <td class="text-right">৳{{$data->credit}}</td>
                                          </tr>
                                    @endforeach
                                  </tbody>
                                  <tfoot>
                                    <tr>
                                      <th style='text-align:right;' colspan='3'>Total</th>
                                      <th style="text-align:right;">{{number_format($total_debit,2)}}</th>
                                      <th style="text-align:right;">{{number_format($total_credit,2)}}</th>
                                    </tr>
                                  </tfoot>
                              </table>
                          </div>
                      </div>
                  </div>
              
                  <div class="row">
                      <div class="col-xs-12">
                          <b>Total in Word (Total):</b> {{Terbilang::make($journalInvoice->total)}}
                          Taka  <br>
                          <b>Note :<b> {{$journalInvoice->note}} <br>
                          Created By : <b>{{auth()->user()->name}}</b>,Printed By <b>{{auth()->user()->name}}</b>, Print Time : {{date('d-m-Y h:i:s')}}
                      </div>
                  </div>
                  <div class="footer">
                          Software Developed by Ongsho
                  </div>
              </div>


            </div>
          </div>
      </div><!-- /.container-fluid -->
    </section>
  @endsection

  @section('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js" integrity="sha512-d5Jr3NflEZmFDdFHZtxeJtBzk0eB+kkRXWFQqEc1EKmolXjHm2IKCA7kTvXBNjIYzjXfD5XzIjaaErpkZHCkBg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  {{-- @include('backend.invoice.internal-assets.js.script') --}}

  <script>
    
  </script>
  @endsection