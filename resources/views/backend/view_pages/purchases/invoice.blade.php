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
            <h1 class="m-0">Purchase Invoice History</h1>
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
              // $number_convert=new Terbilang;
              $info=App\Models\CompanyInformations::first();
              $voucer_invoice=App\Models\Voucer::where('pinvoice_id',$invoice->id)->first();
              $previous_due=App\Http\Traits\BalanceTrait::previousBalanceSupplier($invoice->supplier_id,$voucer_invoice->id);
              @endphp
              <div class="float-right clearfix">
                <a target='_blank' class="btn btn-warning" href='{{URL::to('admin/view-pages/purchase-print/'.$invoice->id)}}'>Print</a>
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
                                      Purchase Invoice
                                  </b>
                              </span><br>
                              @php
                              if($invoice->purchase_type==0){
                                $sale_type='Cash Purchase';
                              }elseif($invoice->purchase_type==1){
                                $sale_type="Regular Purchase";
                              }
                              // dd($invoice);
                              @endphp
                              Invoice No :<b>S-{{date('dm',$invoice->dates).substr(date('Y',$invoice->dates),-2).$invoice->id}}</b> <br>
                              @if($invoice->hand_bill!=null)
                              Hand Memo : <b>{{$invoice->hand_bill}}</b><br>
                              @endif
                              Purchase Type :<b>{{$sale_type}}</b> <br>
                              Date : {{date('d-m-Y',intval($invoice->dates))}}
                          </div>
                      </div>
                    </div>
                  
                  <div class="row">
                      <div class="col-xs-6" style="width: 100%;float:left">
                          <br>
                          <table class="table table-bordered">
                            @if(isset($invoice->supplier->name))
                              Supplier : <b>{{($invoice->supplier->name)}}</b> ,  
                              Mobile No : <b>{{$invoice->supplier->phone}}</b> <br>
                              Adress : <b>{{$invoice->supplier->adress}}</b>
                            @endif
                          </table>
                      </div>
                  </div>
              
              
                  <div class="row">
                      <div class="col-md-12">
                          <div class="table-responsive">
                              <table class="table table-bordered table-striped">
                                  <thead>
                                      <tr>
                                          <th class="text-center">SL.</th>
                                          <th width="35%">Product Name</th>
                                          <th class="text-right">Quantity</th>
                                          <th class="text-right">Price</th>
                                          <th class="text-right">Total</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    @php
                                    $i=0;
                                    @endphp
                                    @foreach($invoice->purchase as $purchase)
                                          <tr>
                                              <td class="text-center">{{$i=$i+1}}</td>
                                              <td>{{$purchase->product->product_code.'-'.$purchase->product->name}}</td>
                                              <td class="text-right">{{$purchase->deb_qantity}} {{$purchase->product->unit->name}}</td>
                                              <td class="text-right">৳{{$purchase->price}}</td>
                                              <td class="text-right">৳{{($purchase->deb_qantity*$purchase->price)}}</td>
                                          </tr>
                                      </tbody>
                                    @endforeach
                              </table>
                          </div>
                      </div>
                  </div>
              
                  <div class="row">
                      <div class="col-md-6">
                          <h4>
                               @if($invoice->purchase_type==0)
                                <img src="{{asset('storage/adminlte/dist/img/paid.jpg')}}" alt="">
                               @elseif($invoice->purchase_type==1)
                                <img src="{{asset('storage/adminlte/dist/img/due.png')}}" alt="">
                               @endif
                          </h4>
                      </div>
                      <div class="col-md-6">
                          <table class="table table-bordered">
                            <tr>
                                <th>Invoice Total</th>
                                <td>৳. {{$invoice->total}}</td>
                            </tr>
                              <tr>
                                  <th>Invoice Due</th>
                                  <td>৳. {{$invoice->total_payable}}</td>
                              </tr>
                             
                              <tr>
                                  <th> Paid </th>
                                  @if($invoice->purchase_type==0)
                                  <td>৳. {{$invoice->total_payable}}</td>
                                  @elseif($invoice->purchase_type==1)
                                  <td>৳. 0.00</td>
                                  @endif
                              </tr>

                              @if($invoice->purchase_type!=0 and $invoice->supplier_id!=null)
                              <tr>
                                <th> Previous Due </th>
                                <td>৳. {{$previous_due}}</td>
                              </tr>
                              @endif      
                              <tr>
                                @if($invoice->purchase_type==1)
                                  <th>Total Due </th>
                                  <td>৳. {{$previous_due+$invoice->total_payable}}</td>
                                @endif
                              </tr>
                          </table>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-xs-12">
                          <b>Total in Word (Invoice Due):</b> {{Terbilang::make($invoice->total_payable)}}
                          Taka  <br>
                          <b>Comment :<b> {{$invoice->note}} <br>
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