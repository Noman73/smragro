@php 
    use Riskihajar\Terbilang\Facades\Terbilang;
    // $number_convert=new Terbilang;
    $info=App\Models\CompanyInformations::first();
   
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

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://2aitautomation.com/meherpur/public/themes/backend/bower_components/bootstrap/dist/css/bootstrap.min.css">
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
    
</div><div id="print" class="print mt-5" >
    <div class="row invoice_header">
      <div class="col-xs-5" style="width: 20%; float:left;">
          <img src="{{asset('storage/logo/'.$info->logo)}}" width="100%" alt="Logo">
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
              Receipt No :<b>P-{{date('dm',$vinvoice->date).substr(date('Y',$vinvoice->date),-2).$vinvoice->id}}</b> <br>
              Transaction Type :<b>Debit Voucer</b> <br>
              Date : {{date('d-m-Y',intval($vinvoice->date))}} 
          </div>
      </div>
    </div>
  <div class="row">
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
  </div>
  <div class="row">
      <div class="col-md-12">
          <div class="table-responsive">
              <table class="table table-bordered table-striped">
                  <thead>
                      <tr>
                          <th width="40%" class="text-left">Name</th>
                          <th width="20%" class="text-left">Sub Name</th>
                          <th width="20%" class="text-center">Comment</th>
                          <th width="20%" class="text-right">Ammount</th>
                      </tr>
                  </thead>
                  <tbody>
                    @php
                    $i=0;
                    $total=0;
                    @endphp
                    @foreach($invoice as $data)
                      @if($data->credit==0)
                      @php
                      $total+=floatval($data->debit)
                      @endphp
                          <tr>
                              <td>{{$data->name}}</td>
                              <td class="text-left">{{$data->sub_name}}</td>
                              <td class="text-left">{{($data->comment)}}</td>
                              <td class="text-right">৳{{$data->debit}}</td>
                          </tr>
                        @endif
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      <th style='text-align:right;' colspan='2'>Total</th>
                      <th style="text-align:right;">{{number_format($total,2)}}</th>
                      <th></th>
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
          Created By : <b>{{auth()->user()->name}}</b>,Printed By <b>{{auth()->user()->name}}</b>, Print Time : {{date('d-m-Y h:i:s')}}
      </div>
  </div>
  <div class="footer">
          Software Developed by Ongsho
  </div>
</div>

<script>
    window.print();
    window.onafterprint = function(){ window.close()};
</script>
</body>
</html>
