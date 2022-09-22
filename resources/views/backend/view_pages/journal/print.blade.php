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
<div id="print" class="print mt-5" >
    <div class="row invoice_header">
      <div class="col-xs-5" style="width: 50%; float:left;">
          @include('layouts.adress')
      </div>
      <div class="col-xs-7" style="width: 50%; text-align:right">
          <div style="padding:0px; width:100%; text-align:right;">
              <span style="font-size: 16px;">
                  <b>
                      Fund Transfer
                  </b>
              </span><br>
              @php
              // dd($invoice);
              @endphp
              Receipt No :<b>J-{{date('dm',$journalInvoice->date).substr(date('Y',$journalInvoice->date),-2).$journalInvoice->id}}</b><br>
              Transaction Type :<b>Fund Transfer</b> <br>
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
<script>
    window.print();
    window.onafterprint = function(){ window.close()};
</script>
</body>
</html>
