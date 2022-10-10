
<!DOCTYPE html>
<html>
<head>
<!-- TABLES CSS CODE -->
<title>বিক্রয় চালান</title>
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<style type="text/css">
	body{
		font-family: arial;
		font-size: 12px;
		/*font-weight: bold;*/
		padding-top:15px;
	}

	@media print {
        .no-print { display: none; }
    }
</style>
</head>
@php
    $info=App\Models\CompanyInformations::first();
@endphp
<body onload=""><!--   -->
		<table width="98%" align="center">
		<tr>
			<td align="center">
				<span>													 
                <strong>{{$info->company_name}}</strong><br>
                	{{$info->adress}}<br>
                     Mobile: {{$info->phone}}<br> 
			    </span>
			</td>
		</tr>
		<tr><td align="center"><strong>-----------------চালান-----------------</strong></td></tr>
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td width="40%">Invoice</td>
						<td><b>S-{{date('dm',$invoice->dates).substr(date('Y',$invoice->dates),-2).$invoice->id}}</b></td>
					</tr>
					@if(isset($invoice->customer->name))
					<tr>
						<td>Name</td>
						<td>{{$invoice->customer->name}}</td>
					</tr>
					<tr>
						<td>মোবাইল</td>
						<td>{{$invoice->customer->mobile}}</td>
					</tr>
					@endif
					<tr>
						<td>Date:{{date('d-m-Y',$invoice->dates)}}</td>
						<td style="text-align: right;">Time:01:14 pm</td>
					</tr>
				</table>
				
			</td>
		</tr>
		<tr>
			<td>

				<table width="100%" cellpadding="0" cellspacing="0"  >
					<thead>
					<tr style="border-top-style: dashed;border-bottom-style: dashed;border-width: 0.1px;">
						<th style="font-size: 11px; text-align: left;padding-left: 2px; padding-right: 2px;">Product</th>
						<th style="font-size: 11px; text-align: left;padding-left: 2px; padding-right: 2px;">Price</th>
						<th style="font-size: 11px; text-align: center;padding-left: 2px; padding-right: 2px;">Quantity</th>
						<th style="font-size: 11px; text-align: right;padding-left: 2px; padding-right: 2px;">Total</th>
					</tr>
					</thead>
					<tbody style="border-bottom-style: dashed;border-width: 0.1px;">
                        @foreach($invoice->sales as $sales)
						<tr>
                            <td style='padding-left: 2px; padding-right: 2px;'>{{$sales->product->product_code.'-'.$sales->product->name}}</td>
                            <td style='padding-left: 2px; padding-right: 2px;'>{{$sales->price}}</td>
                            <td style='text-align: center;padding-left: 2px; padding-right: 2px;'>{{$sales->deb_qantity}} {{$sales->product->unit->name}}</td>
                            <td style='text-align: right;padding-left: 2px; padding-right: 2px;' >{{$sales->price*$sales->deb_qantity}}</td>
                        </tr>
                        @endforeach					
				   </tbody>
					<tfoot>
					 <!-- <tr><td colspan="5"><hr></td></tr>    -->
					 <tr >
						<td style=" padding-left: 2px; padding-right: 2px;" colspan="5" align="right">Total</td>
						<td style=" padding-left: 2px; padding-right: 2px;" align="right">{{$invoice->total}}</td>
					</tr>
					<tr >
						<td style=" padding-left: 2px; padding-right: 2px;" colspan="5" align="right">Discount</td>
						<td style=" padding-left: 2px; padding-right: 2px;" align="right">{{$invoice->discount}}</td>
					</tr>
					 <tr >
						<td style=" padding-left: 2px; padding-right: 2px;" colspan="4" align="right">Vat</td>
						<td style=" padding-left: 2px; padding-right: 2px;" align="right">{{$invoice->vat}}</td>
					</tr> 
					<!-- <tr>
	                     <td style=' padding-left: 2px; padding-right: 2px;' colspan='4' align='right'>Tax Amt</td>
	                      <td style=' padding-left: 2px; padding-right: 2px;' align='right'>0.00</td>
	                </tr> -->
	                <tr>
						<td style=" padding-left: 2px; padding-right: 2px;" colspan="5" align="right">Total Payable</td>
						<td style=" padding-left: 2px; padding-right: 2px;" align="right">{{$invoice->total_payable}}</td>
					</tr>
	                					

					<!-- <tr><td style="border-bottom-style: dashed;border-width: 0.1px;" colspan="5"></td></tr>   -->
					<tr>
						<td style=" padding-left: 2px; padding-right: 2px;font-weight: bold;" colspan="5" align="right">Total Paid</td>
						@if($invoice->sale_type==0)
                        <td style=" padding-left: 2px; padding-right: 2px;font-weight: bold;" colspan="5" align="right">৳. {{$invoice->total_payable}}</td>
                        @elseif($invoice->sale_type==1)
                        <td style=" padding-left: 2px; padding-right: 2px;font-weight: bold;" colspan="5" align="right">৳. {{$invoice->pay->sum('debit')}}</td>
                        @else
                        <td style=" padding-left: 2px; padding-right: 2px;font-weight: bold;" colspan="5" align="right">৳. {{$invoice->pay->sum('debit')}}</td>
                        @endif
					</tr>
					
					<!-- change_return_status -->
						<tr>
                            @if($invoice->sale_type==1)
                            <th style=" padding-left: 2px; padding-right: 2px;" colspan="5" align="right">Current Due </th>
                            {{-- <td>৳. {{$previous_due+$invoice->total_payable}}</td> --}}
                            <td style=" padding-left: 2px; padding-right: 2px;" colspan="5" align="right">৳. {{App\Http\Traits\BalanceTrait::customerBalance($invoice->customer_id)}}</td>
                            @elseif($invoice->sale_type==2)
                            <th style=" padding-left: 2px; padding-right: 2px;" colspan="5" align="right">Total Due </th>
                            <td style=" padding-left: 2px; padding-right: 2px;" colspan="5" align="right">৳. {{floatval($invoice->total_payable)-floatval($invoice->pay->sum('debit'))}}</td>
                            @endif
						</tr>
					
					<tr>
						<td colspan="6" align="center">----------Thank You! Come Again----------</td>
					</tr>
					<tr>
						<td colspan="6" align="center">
						
							{{-- <div style="display:inline-block;vertical-align:middle;line-height:16px !important;">	
								<img class="center-block" style=" width: 100%; opacity: 1.0" src="https://posmatrix.nibiz.xyz/barcode/SL0088">
							</div> --}}
						    
						</td>
					</tr>

					</tfoot>
				</table>
				<center style="padding-top:5px;">Powered by Ongsho</center>
			</td>
		</tr>
	</table>
	<center >
  <div class="row no-print">
  <div class="col-md-12">
  <div class="col-md-2 col-md-offset-5 col-xs-4 col-xs-offset-4 form-group">
    <button type="button" id="" class="btn btn-block btn-success btn-xs" onclick="window.print();" title="Print">Print</button>
       </div>
   </div>
   </div>
</center>
		<style type="text/css" media="print">
		@page {
		    size: auto;   /* auto is the initial value */
		    margin: 0;  /* this affects the margin in the printer settings */
		}
	</style>
    <script type="text/javascript">
        window.print();
        window.onfocus=function(){ window.close();}
    </script>


</body>
</html>