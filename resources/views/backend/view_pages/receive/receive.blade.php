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
            <h1 class="m-0">Receive History</h1>
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
                <a target='_blank' class="btn btn-warning" href='{{URL::to('admin/view-pages/receive-print/'.$vinvoice->id)}}'>Print</a>
              </div>  
                  <div id="print" class="print mt-5" >
                    <div class="row invoice_header">
                      <div class="col-xs-5" style="width: 20%; float:left;">
                        @include('layouts.adress')
                      </div>
                      <div class="col-xs-7" style="width: 80%; text-align:right">
                          <div style="padding:5px; width:100%; text-align:right;">
                              <span style="font-size: 16px;">
                                  <b>
                                      Receipt
                                  </b>
                              </span><br>
                              @php
                              
                              // dd($invoice);
                              @endphp
                              Receipt No :<b>R-{{date('dm',$vinvoice->date).substr(date('Y',$vinvoice->date),-2).$vinvoice->id}}</b> <br>
                              TRX Type :<b>Credit Voucer</b> <br>
                              Date : {{date('d-m-Y',intval($vinvoice->date))}} 
                          </div>
                      </div>
                    </div>
                  <div class="row">
                      <div class="col-xs-6" style="width: 100%;float:left">
                          <br>
                          <table class="table table-bordered">
                            @foreach($invoice as $method)
                            @if($method->debit!=0)
                              <u><p class="h5">Receive by : <b>{{$method->name}}</b> </p></u>
                              @if($method->sub_name!='')  
                              Name : <b>{{$method->sub_name}}</b> <br>
                              @endif
                              
                            @endif
                            @endforeach
                          </table>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12">
                          <div class="table-responsive">
                              <table class="table table-bordered table-striped">
                                  <thead>
                                      <tr>
                                          <th width="40%" class="text-left">Name</th>
                                          <th width="20%" class="text-center">Comments</th>
                                          <th width="20%" class="text-right">Amount</th>
                                          
                                      </tr>
                                  </thead>
                                  <tbody>
                                    @php
                                    $i=0;
                                    $total=0;
                                    @endphp
                                    @foreach($invoice as $data)
                                      @if($data->debit==0)
                                      @php
                                      $total+=floatval($data->credit)
                                      @endphp
                                          <tr>
                                              <td>{{$data->name.($data->sub_name!=null ? ' - '.$data->sub_name : '' )}}</td>
                                              <td class="text-left">{{($data->comment)}}</td>
                                              <td class="text-right">৳{{$data->credit}}</td>
                                          </tr>
                                        @endif
                                    @endforeach
                                  </tbody>
                                  <tfoot>
                                    <tr>
                                      <th style='text-align:right;' colspan='2'>Total</th>
                                      <th style="text-align:right;">{{number_format($total,2)}}</th>
                                    </tr>
                                  </tfoot>
                              </table>
                          </div>
                      </div>
                  </div>
              
                  <div class="row">
                      <div class="col-xs-12">
                          <b>Total in Word (Total):</b> {{Terbilang::make($total)}}
                          Taka  <br>
                          <b>Note :<b> {{$vinvoice->note}} <br>
                          Created By : <b>{{auth()->user()->name}}</b>,Printed By : <b>{{auth()->user()->name}}</b>, Print Time : {{date('d-m-Y h:i:s')}}
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