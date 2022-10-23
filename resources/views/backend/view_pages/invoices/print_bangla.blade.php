@php 
    use Riskihajar\Terbilang\Facades\Terbilang;
    use Rakibhstu\Banglanumber\NumberToBangla;
    $numto = new NumberToBangla();
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

    <title>SMRAGRO</title>

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
          <div class="col-xs-5" style="width: 25%; float:left;">
              <img src="{{asset('storage/logo/'.$info->logo)}}" width="100%" alt="Logo">
              {{$info->adress}}<br>
              {{$info->phone}}<br>
              {{$info->email}}<br>
              {{$info->web}}<br>
              বিন নম্বর: {{$info->bin_no}}<br>
          </div>
          <div class="col-xs-7" style="width: 75%; text-align:right">
              <div style=" width:100%; text-align:right;">
                  <span style="font-size: 16px;">
                      <b>
                          বিক্রয় ইনভয়েস
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
                  ইনভয়েস নম্বর:<b>S-{{date('dm',$invoice->dates).substr(date('Y',$invoice->dates),-2).$invoice->id}}</b> <br>
                  @if($invoice->hand_bill!=null)
                  হ্যান্ড মেমো নম্বর : <b>{{$invoice->hand_bill}}</b><br>
                  @endif
                  বিক্রয় ধরন :<b>{{$sale_type}}</b> <br>
                  তারিখ : {{date('d-m-Y',intval($invoice->dates))}}<br/>
                  
              </div>
          </div>
        </div>
      
      <div class="row">
          <div class="col-xs-6" style="width: 50%;float:left">
              <br>
              <table class="table table-bordered">
                @if(isset($invoice->customer->name))
                  ক্রেতা : <b>{{($invoice->customer->name)}}</b> ,  
                  মোবাইল নম্বর : <b>{{$invoice->customer->phone}}</b> <br>
                  ঠিকানা : <b>{{$invoice->customer->adress}}</b>
                @endif
              </table>
          </div>
          @if($invoice->sale_by==2)
          <div class="col-xs-6" style="width:50%;text-align:right;float:right;border :1 px solid black;">
            <br>
            <table class="table table-bordered">
              @if(isset($invoice->shipping_customer->name))
                  <strong>Shipping to :</strong><br/>
                  ক্রেতা : <b>{{($invoice->shipping_customer->name)}}</b> ,  
                  মোবাইল : <b>{{$invoice->shipping_customer->phone}}</b> <br/>
                  ঠিকানা : <b>{{$invoice->shipping_customer->adress}}</b><br/>
                  কুরিয়ার : <b>{{$invoice->courier->name}}</b>
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
                              <th class="text-center" width="10%">ক্রমিক নং.</th>
                              <th width="35%">পন্যের নাম</th>
                              <th class="text-right">পরিমান</th>
                              <th class="text-right">মূল্য</th>
                              <th class="text-right">মোট</th>
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
          <div class="col-md-6" style='width: 50%;float:left;'>
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
                <p>কন্ডিশন টাকা : {{floatval($invoice->total_payable)-floatval( (isset($invoice->condition_amount->debit)? $invoice->condition_amount->debit : 0.00) )}}</p>
              @endif
              @if($invoice->sale_by==2)
                <p class='h4 font-weight-bold'>কন্ডিশন টাকা: {{$invoice->cond_amount}}</p> 
              @endif
                <p class="font-weight-bold">নোট : {{($invoice->notes !=null ?$invoice->notes->note : '' )}}</p> 
          </div>
          <div class="col-md-6" style="width:50%;float: right;">
              <table class="table table-bordered">
              
                <tr>
                    <th>মোট ইনভয়েস</th>
                    <td>৳. {{$invoice->total}}</td>
                </tr>
                <tr>
                    <th>ছাড়</th>
                    <td>৳ {{($invoice->discount_type==0 ? number_format($invoice->discount,2) : number_format(floatval($invoice->discount*$invoice->total)/100,2))}}</td>
                </tr>
                <tr>
                  <th>ভ্যাট</th>
                  <td>৳ {{number_format(($invoice->vat*$invoice->total)/100,2)}}</td>
                </tr>
                <tr>
                  <th>পরিবহন বাবদ</th>
                  <td>৳ {{number_format($invoice->transport==null ? 0 : $invoice->transport,2)}}</td>
                </tr>
                  <tr>
                      <th>ইনভয়েসে বাকি </th>
                     
                      <td>৳. {{$invoice->total_payable}}</td>
                  </tr>
                 
                  <tr>
                      <th> পরিশোধ </th>
                      @if($invoice->sale_type==0)
                      <td>৳. {{$invoice->total_payable}}</td>
                      @elseif($invoice->sale_type==1)
                      <td>৳ {{number_format($invoice->pay->sum('debit'),2)}}</td>
                      @else
                      <td>৳ {{number_format($invoice->pay->sum('debit'),2)}}</td>
                      @endif
                  </tr>

                  @if($invoice->sale_type!=0 and $invoice->customer_id!=null and date('d-m-Y',$invoice->dates)===date('d-m-Y'))
                  <tr>
                    <th> আগের বাকি </th>
                    <td>৳. {{$previous_due}}</td>
                  </tr>
                  @endif  
                  <tr>
                    @if($invoice->sale_type==1)
                    <th>বর্তমান বাকি </th>
                                  {{-- <td>৳. {{$previous_due+$invoice->total_payable}}</td> --}}
                    <td>৳. {{App\Http\Traits\BalanceTrait::customerBalance($invoice->customer_id)}}</td>
                    @elseif($invoice->sale_type==2)
                    <th>মোট বাকি </th>
                    <td>৳. {{floatval($invoice->total_payable)-floatval($invoice->pay->sum('debit'))}}</td>
                    @endif
                  </tr>
                  @if($invoice->sale_by==2)
                    <tr>
                        <th>কন্ডিশন </th>
                        <td>৳. {{$invoice->cond_amount}}</td>
                    </tr>
                  @endif
              </table>
          </div>
      </div>
      <div class="row">
          <div class="col-xs-12">
        <b>সর্বমোট কথায় (ইনভয়েসে বাকি):</b> {{$numto->bnWord($invoice->total_payable)}}
         টাকা <br>
                          
              <b>মন্তব্য :<b> {{$invoice->staff_note}} <br>
                প্রস্তুতকারক : <b>{{$invoice->user->name}}</b>,প্রিন্ট করেছেন <b>{{auth()->user()->name}}</b>, Print Time : {{date('d-m-Y h:i:s')}}
          </div>
      </div>
      <div class="row footer">
          <div style="text-align:center;" class="col-xs-12 col-12 text-center">
              সফটওয়ার তৈরী করেছেন অংশ
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
