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
        <div class="text-center" style="line-height:0.0;margin-top:20px;"><span style="font-size:14px;">Minister of republic Bangladesh</span></div>
        <div class="text-center" style="line-height:0;margin-top:20px;font-weight:bold;margin-left:85px;"><span style="font-size:16px;">National Board of Revenue</span><span style="float:right;border:2px solid black; padding:10px;margin-top:-20px;">Mushok : 6.3</span></div>
        <div class="text-center font-weight-bold h5" style="line-height: 0.9;"><p>VAT Invoice</p></div>
        <P class="text-center">[<b>Note<b/> : Clause (GA) and clause (Cha) of sub-rule 1 of Rule 40]</P>
        <div class="row invoice_header">
          <div class="col-md-6" style=" float:left;">
              @include('layouts.adress')
          </div>
          <div class="col-md-6" style=" text-align:right">
              <div style=" width:100%; text-align:right;">
                  <br>
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
          <div class="col-xs-12" style="width: 100%;float:left">
              <br>
              <table class="table table-bordered">
                @if(isset($invoice->customer->name))
                  Customer : <b>{{($invoice->customer->name)}}</b> ,  
                  Customer BIN : <b>{{$invoice->customer->phone}}</b> <br>
                  Adress : <b>{{$invoice->customer->adress}}</b>
                @endif
              </table>
          </div>
          {{-- @if($invoice->sale_by==2)
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
          @endif --}}
          {{-- @if($invoice->sale_by==1)
            <div class="col-xs-6" style="width:50%;text-align:right">
                <br/>
                Courier : <b>{{$invoice->courier->name}}</b>
            </div>
          @endif --}}
      </div>
  

      <div class="row" style="margin-top:-100px;">
        <div class="col-12">
            <div class="container">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product </th>
                            <th>Qantity</th>
                            <th>Price</th>
                            <th>Total Price</th>
                            <th>Vat %</th>
                            <th>Vat Amount</th>
                            <th>Total With Vat</th>
                        </tr>
                    </thead>
                    <tbody>
                      @php
                      $total_price=0;
                      $total_with_vat=0;
                      $total_vat=0;
                      @endphp
                      @foreach($invoice->sales as $sales)
                      @php
                      $price=floatval($sales->price)-((floatval($sales->price)*15)/100);
                      $total_price+=floatval($sales->deb_qantity)*floatval($price);
                      $total_with_vat+=(floatval($sales->deb_qantity)*$price)+((floatval($sales->price)*15)/100);
                      $total_vat+=((floatval($sales->deb_qantity)*$sales->price)*15)/100
                      @endphp
                        <tr>
                            <td>{{$sales->product->name}}</td>
                            <td>{{$sales->deb_qantity.' '. $sales->product->unit->name}}</td>
                            <td>{{$price}}</td>
                            <td>{{floatval($sales->deb_qantity)*$price}}</td>
                            <td>15%</td>
                            <td>{{((floatval($sales->deb_qantity)*$sales->price)*15)/100}}</td>
                            <td>{{number_format($total_with_vat,2)}}</td>
                        </tr>
                       
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr style="font-weight: bold;">
                        <td colspan="3" class="text-right">Total=</td>
                        <td>{{$total_price}}</td>
                        <td></td>
                        <td>{{number_format($total_vat,2)}}</td>
                        <td>{{number_format($total_with_vat,2)}}</td>
                      </tr>
                      <tr style="font-weight: bold;">
                        <td colspan="3" class="text-right">Discount=</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{($invoice->discount_type==0 ? number_format($invoice->discount,2) : number_format(floatval($invoice->discount*$invoice->total)/100,2))}}</td>
                      </tr>
                      <tr style="font-weight: bold;">
                        <td colspan="3" class="text-right">Transport Income=</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{number_format($invoice->transport==null ? 0 : $invoice->transport,2)}}</td>
                      </tr>
                      <tr style="font-weight: bold;">
                        <td colspan="3" class="text-right">Sub Total=</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{number_format($invoice->total_payable,2)}}</td>
                      </tr>
                    </tfoot>
                </table>
            </div>
        </div>
      </div>
  


      <div style="margin-bottom:20px;margin-top:20px;" class="row">
        <div class="col-md-6">
          <span style="text-decoration:overline;margin-right:25px;">Authorized Signature</span>
        </div>
      </div>
      <div class="row">
          <div class="col-xs-12">
        {{-- <b>Total in Word (Invoice Due):</b> {{Terbilang::make($invoice->total_payable)}}
        Taka  <br> --}}
                          
              {{-- <b>Comment :<b> {{$invoice->staff_note}} <br> --}}
              Created By : <b>{{$invoice->user->name}}</b>,Printed By <b>{{auth()->user()->name}}</b>, Print Time : {{date('d-m-Y h:i:s')}}
          </div>
      </div>
      <div class="row footer">
          <div style="text-align:center;" class="col-xs-12 col-12 text-center">
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
