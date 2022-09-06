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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    <div id="print" class="print" >
        <div class="row invoice_header">
          <div class="col-xs-5" style="width:50%; float:left;">
              @include('layouts.adress')
          </div>
          <div class="col-xs-7" style="width: 50%; text-align:right">
              <div style=" width:100%; text-align:right;">
                  <span style="font-size: 16px;">
                      <b>
                          Customer Details
                      </b>
                  </span><br>
                  Date : {{date('d-M-Y')}}<br/>
              </div>
          </div>
        </div>
      
      <div class="row">
          <div class="col-xs-6" style="width: 100%;float:left">
              <br>
              <table class="table table-bordered">
                @if(isset($customer))
                <tr>
                    <td>Name </td>
                    <td class="text-right">{{$customer->name}}</td>
                </tr>
                <tr>
                    <td>Phone </td>
                    <td class="text-right">{{$customer->phone}}</td>
                </tr>
                <tr>
                    <td>Email </td>
                    <td class="text-right">{{$customer->email}}</td>
                </tr>
                <tr>
                    <td>Adress </td>
                    <td class="text-right">{{$customer->adress}}</td>
                </tr>
                <tr>
                    <td>Birth Date </td>
                    <td class="text-right">{{$customer->birth_date}}</td>
                </tr>
                <tr>
                    <td>Nid </td>
                    <td class="text-right">{{$customer->nid}}</td>
                </tr>
                <tr>
                    <td>Current Balance </td>
                    <td class="text-right">{{$customer->current_balance}}</td>
                </tr>
                <tr>
                    <td>Last Transaction </td>
                    <td class="text-right">{{date('d-M-Y',$customer->last_trx)}}</td>
                </tr>
                @endif
              </table>
          </div>
      </div>

      <div class="row">
          <div class="col-xs-12">
        <b>Current Balance in Word (Invoice Due): {{Terbilang::make($customer->current_balance)}}</b>
        Taka  <br>
              Created By : <b>{{$customer->author->name}}</b>,Printed By <b>{{auth()->user()->name}}</b>, Print Time : {{date('d-m-Y h:i:s')}}
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
