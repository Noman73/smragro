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
            <h1 class="m-0">Sales Invoice History</h1>
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
               $voucer_invoice=App\Models\Voucer::where('invoice_id',$invoice->id)->first();
              //  dd($voucer_invoice->id);
              $previous_due=App\Http\Traits\BalanceTrait::previousBalance($invoice->customer_id,$voucer_invoice->id,$voucer_invoice->dates);
              @endphp
              <div class="float-right clearfix">
                <a target='_blank' class="btn btn-warning" href='{{URL::to('admin/view-pages/sales-invoice-print/'.$invoice->id)}}'>Print</a>
                <a target='_blank' class="btn btn-warning" href='{{URL::to('admin/view-pages/sales-invoice-bangla-print/'.$invoice->id)}}'>Bangla Print</a>
                <a target='_blank' class="btn btn-secondary" href='{{URL::to('admin/view-pages/sales-chalan-invoice-print/'.$invoice->id)}}'>Chalan</a>
                <a target='_blank' class="btn btn-info" href='{{URL::to('admin/view-pages/sales-total-chalan-invoice-print/'.$invoice->id)}}'>Total Chalan</a>
              </div>  
                  <div id="print" class="print mt-5" >
                    <div class="row invoice_header">
                      <div class="col-xs-8" style="width:60%;float:left;">
                          @include('layouts.adress')
                      </div>
                      <div class="col-xs-4" style="width:40%; text-align:right">
                          <div style="padding:5px; width:100%; text-align:right;">
                              <span style="font-size: 16px;">
                                  <b>
                                      Sale Invoice
                                  </b>
                              </span><br>
                              @php
                              if($invoice->sale_type==0){
                                $sale_type='Cash Sale';
                              }elseif($invoice->sale_type==1){
                                $sale_type="Regular Sale";
                              }else{
                                $sale_type="Condition Sale";
                              }
                              // dd($invoice);
                              @endphp
                              Invoice No :<b>S-{{date('dm',$invoice->dates).substr(date('Y',$invoice->dates),-2).$invoice->id}}</b> <br>
                              @if($invoice->hand_bill!=null)
                              Hand Memo : <b>{{$invoice->hand_bill}}</b><br>
                              @endif
                              Sale Type :<b>{{$sale_type}}</b> <br>
                              Date : {{date('d-m-Y',intval($invoice->dates))}}<br/>
                          </div>
                      </div>
                    </div>
                  
                  <div class="row">
                      <div class="col-xs-6" style="width: 50%;float:left">
                          <br>
                          <table class="table table-bordered">
                            @if(isset($invoice->customer->name))
                              Customer : <b>{{($invoice->customer->name)}}</b> ,  
                              Mobile No : <b>{{$invoice->customer->phone}}</b> <br>
                              Adress : <b>{{$invoice->customer->adress}}</b>
                            @endif
                          </table>
                      </div>
                      @if($invoice->sale_by==2)
                      <div class="col-xs-6" style="width:50%;text-align:right">
                        <br>
                        <table class="table table-bordered">
                          @if(isset($invoice->shipping_customer->name))
                              <strong>Shipping to :</strong><br/>
                              Customer : <b>{{($invoice->shipping_customer->name)}}</b> ,  
                              Mobile No : <b>{{$invoice->shipping_customer->phone}}</b> <br>
                              Adress : <b>{{$invoice->shipping_customer->adress}}</b><br/>
                              Courier : <b>{{$invoice->courier->name}}</b>
                          @endif
                        </table>
                      </div>
                      @endif
                  </div>
              
              
                  <div class="row">
                      <div class="col-md-12">
                          <div class="table-responsive">
                              <table class="table table-bordered table-striped">
                                  <thead>
                                      <tr>
                                          <th class="text-center" width="10%">SL.</th>
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
                                    @foreach($invoice->sales as $sales)
                                          <tr>
                                              <td class="text-center">{{$i=$i+1}}</td>
                                              <td>{{$sales->product->product_code.'-'.$sales->product->name}}</td>
                                              <td class="text-right">{{$sales->deb_qantity}} {{$sales->product->unit->name}}</td>
                                              <td class="text-right">৳{{$sales->price}}</td>
                                              <td class="text-right">৳{{($sales->deb_qantity*$sales->price)}}</td>
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
                               @if($invoice->sale_type==0)
                                <img src="{{asset('storage/adminlte/dist/img/paid.jpg')}}" alt="">
                               @elseif($invoice->sale_type==1 and $invoice->pay->sum('debit')>=$invoice->total_payable)
                               <img src="{{asset('storage/adminlte/dist/img/paid.jpg')}}" alt="">
                               @elseif($invoice->sale_type==1 and $invoice->pay->sum('debit')<$invoice->total_payable)
                               <img src="{{asset('storage/adminlte/dist/img/due.png')}}" alt="">
                               @elseif($invoice->sale_type==2 and $invoice->pay->sum('debit')>=$invoice->total_payable)
                               <img src="{{asset('storage/adminlte/dist/img/paid.jpg')}}" alt="">
                               @elseif($invoice->sale_type==2 and $invoice->pay->sum('debit')<$invoice->total_payable)
                               <img src="{{asset('storage/adminlte/dist/img/due.png')}}" alt="">
                               @endif
                          </h4>
                          @if($invoice->sale_type==2)
                          <p class='h4 font-weight-bold'>Condition Taka : {{floatval($invoice->total_payable)-floatval( (isset($invoice->condition_amount->debit)? $invoice->condition_amount->debit : 0.00) )}}</p>
                          @endif
                          @if($invoice->sale_by==2)
                          <p class='h4 font-weight-bold'>Condition Taka: {{$invoice->cond_amount}}</p> 
                          @endif
                          <p class="font-weight-bold"> {{($invoice->notes !=null ?'Note : '.$invoice->notes->note : '' )}}</p> 
                         
                      </div>
                      <div class="col-md-6">
                          <table class="table table-bordered">
                            
                            <tr>
                                <th>Invoice Total</th>
                                <td>৳ {{$invoice->total}}</td>
                            </tr>
                            <tr>
                                <th>Discount</th>
                                <td>৳ {{($invoice->discount_type==0 ? number_format($invoice->discount,2) : number_format(floatval($invoice->discount*$invoice->total)/100,2))}}</td>
                            </tr>
                            <tr>
                              <th>Vat</th>
                              <td>৳ {{number_format(($invoice->vat*$invoice->total)/100,2)}}</td>
                            </tr>
                            <tr>
                              <th>Transport Income</th>
                              <td>৳ {{number_format($invoice->transport==null ? 0 : $invoice->transport,2)}}</td>
                            </tr>
                              <tr>
                                  <th>Invoice Due</th>
                                 
                                  <td>৳ {{$invoice->total_payable}}</td>
                              </tr>
                             
                              <tr>
                                  <th> Paid </th>
                                  @if($invoice->sale_type==0)
                                  <td>৳ {{$invoice->total_payable}}</td>
                                  @elseif($invoice->sale_type==1)
                                  <td>৳ {{number_format($invoice->pay->sum('debit'),2)}}</td>
                                  @else
                                  <td>৳ {{number_format($invoice->pay->sum('debit'),2)}}</td>
                                  @endif
                              </tr>

                              @if($invoice->sale_type!=0 and $invoice->customer_id!=null and date('d-m-Y',$invoice->dates)===date('d-m-Y'))
                              <tr>
                                <th> Previous Due </th>
                                <td>৳ {{$previous_due}}</td>
                              </tr>
                              @endif    
                              <tr>
                                @if($invoice->sale_type==1)
                                  <th>Current Due </th>
                                  {{-- <td>৳. {{$previous_due+$invoice->total_payable}}</td> --}}
                                  <td>৳ {{App\Http\Traits\BalanceTrait::customerBalance($invoice->customer_id)}}</td>
                                @elseif($invoice->sale_type==2)
                                <th>Total Due </th>
                                <td>৳ {{floatval($invoice->total_payable)-floatval($invoice->pay->sum('debit'))}}</td>
                                @endif
                              </tr>
                              @if($invoice->sale_by==2)
                              <tr>
                                <th>Condition Amount</th>
                                <td>৳ {{$invoice->cond_amount}}</td>
                              </tr>
                              @endif
                          </table>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-xs-12">
                          <b>Total in Word (Invoice Due):</b> {{Terbilang::make($invoice->total_payable)}}
                          Taka  <br>
                          <b>Comment :<b> {{$invoice->staff_note}} <br>
                          Created By : <b>{{$invoice->user->name}}</b>,Printed By <b>{{auth()->user()->name}}</b>, Print Time : {{date('d-m-Y h:i:s')}}
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