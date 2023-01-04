@php 
    use Riskihajar\Terbilang\Facades\Terbilang;
    // $number_convert=new Terbilang;
    $info=App\Models\CompanyInformations::first();
    $voucer_invoice=App\Models\Voucer::where('invoice_id',$invoice->id)->first();
    $previous_due=App\Http\Traits\BalanceTrait::previousBalance($invoice->customer_id,$voucer_invoice->id);
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>{{$info->company_name}}</title>

    <!--Favicon-->
    <link rel="icon" href="https://2aitautomation.com/meherpur/public/img/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    
    <style>
        body{
            font-size: 10px;
        }
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
            margin-top:25px;
            text-align: center;
            
        }
        @page  {
            /* size: A5; */
            margin-bottom: 50px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div id="print" class="print" >
        <div class="row invoice_header">
          <div class="col-xs-6" style="width: 50%; float:left;">
            @include('layouts.adress')
              
          </div>
          <div class="col-xs-6" style="width: 50%; text-align:right">
              <div style=" width:100%; text-align:right;">
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
                  Customer : <b>{{($invoice->customer->name)}}</b><br> 
                  Mobile No : <b>{{$invoice->customer->phone}}</b> <br>
                  Adress : <b>{{$invoice->customer->adress}}</b>
                @endif
              </table>
          </div>
          @if($invoice->sale_by==2)
          <div class="col-xs-6" style="width:50%;text-align:right;float:right;border :1 px solid black;">
            <br>
            <table class="table table-bordered">
              @if(isset($invoice->shipping_customer->name))
                  <strong>Shipping to :</strong><br/>
                  Customer : <b>{{($invoice->shipping_customer->name)}}</b> ,  
                  Mobile No : <b>{{$invoice->shipping_customer->phone}}</b> <br>
                  Adress : <b>{{$invoice->shipping_customer->adress}}</b>
                  Courier : <b>{{$invoice->shipping_customer->adress}}</b>
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
                              <th width="10%" class="text-center">SL.</th>
                              <th width="70%">Product Name</th>
                              <th width="20%" class="text-right">Quantity</th>
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
                              </tr>
                          </tbody>
                        @endforeach
                  </table>
              </div>
          </div>
      </div>
  
      
      <div class="row">
          <div class="col-xs-12">    
              <b>Comment :<b> {{$invoice->staff_note}} <br>
              Created By : <b>{{$invoice->user->name}}</b>,Printed By <b>{{auth()->user()->name}}</b>, Print Time : {{date('d-m-Y h:i:s')}}
          </div>
      </div>
      <div class="row footer">
          <span>This report has been taken from ERP, hence No signature required. </span>
          <div class="col-xs-12 col-12 text-center">
              Software Developed by Ongsho
          </div>
      </div>
  </div>
</div>

<script>
    window.print();
    window.onafterprint = function(){ window.close()};
</script>
</body>
</html>
