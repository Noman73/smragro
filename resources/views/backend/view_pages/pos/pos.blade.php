
<!DOCTYPE html>
<html>
<head>
<!-- TABLES CSS CODE -->
<title>বিক্রয় চালান</title>
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="https://posmatrix.nibiz.xyz/theme/bootstrap/css/bootstrap.min.css">
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
<body onload=""><!--   -->
		<table width="98%" align="center">
		<tr>
			<td align="center">
				<span>													 
                <strong>Kazi Super Shop</strong><br>
                	Shop no-2/D plot no-2<br> 
		            Uttara		            		            <br>
		            		            		            ফোন: 9999999999<br> 
			</span>
			</td>
		</tr>
		<tr><td align="center"><strong>-----------------চালান-----------------</strong></td></tr>
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td width="40%">Invoice</td>
						<td><b>#SL0088</b></td>
					</tr>
					<tr>
						<td>Name</td>
						<td>{{$invoice->customer->name}}</td>
					</tr>
					<tr>
						<td>মোবাইল</td>
						<td>{{$invoice->customer->mobile}}</td>
					</tr>
					<tr>
						<td>Date:{{date('d-m-Y',$invoice->dates)}}</td>
						<td style="text-align: right;">সময়:01:14 pm</td>
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
						<tr>
                            <td style='padding-left: 2px; padding-right: 2px;' valign='top'>1</td>
                            <td style='padding-left: 2px; padding-right: 2px;'>cadbury  dairy p/n (110gm)</td>
                            <td style='padding-left: 2px; padding-right: 2px;'>300.00</td>
                            <td style='text-align: center;padding-left: 2px; padding-right: 2px;'>2.00</td>
                            <td style='text-align: right;padding-left: 2px; padding-right: 2px;' >600.00</td>
                        </tr>					
				   </tbody>
					<tfoot>
					 <!-- <tr><td colspan="5"><hr></td></tr>    -->
					 <tr >
						<td style=" padding-left: 2px; padding-right: 2px;" colspan="5" align="right">কর প্রদানের আগে</td>
						<td style=" padding-left: 2px; padding-right: 2px;" align="right">600.00</td>
					</tr>
					<tr >
						<td style=" padding-left: 2px; padding-right: 2px;" colspan="5" align="right">করের পরিমাণ</td>
						<td style=" padding-left: 2px; padding-right: 2px;" align="right">0.00</td>
					</tr>
					<!-- <tr >
						<td style=" padding-left: 2px; padding-right: 2px;" colspan="4" align="right">উপমোট</td>
						<td style=" padding-left: 2px; padding-right: 2px;" align="right">600.00</td>
					</tr> -->
					<!-- <tr>
	                     <td style=' padding-left: 2px; padding-right: 2px;' colspan='4' align='right'>Tax Amt</td>
	                      <td style=' padding-left: 2px; padding-right: 2px;' align='right'>0.00</td>
	                </tr> -->
	                <tr>
						<td style=" padding-left: 2px; padding-right: 2px;" colspan="5" align="right">অন্যান্য চার্জ</td>
						<td style=" padding-left: 2px; padding-right: 2px;" align="right">0.00</td>
					</tr>
	                					

					<!-- <tr><td style="border-bottom-style: dashed;border-width: 0.1px;" colspan="5"></td></tr>   -->
					<tr>
						<td style=" padding-left: 2px; padding-right: 2px;font-weight: bold;" colspan="5" align="right">মোট</td>
						<td style=" padding-left: 2px; padding-right: 2px;font-weight: bold;" align="right">600.00</td>
					</tr>
					
					<!-- change_return_status -->
											<tr>
							<td style=" padding-left: 2px; padding-right: 2px;" colspan="5" align="right">পরিশোধিত পেমেন্ট</td>
							<td style=" padding-left: 2px; padding-right: 2px;" align="right">600.00</td>
						</tr>
						<tr>
							<td style=" padding-left: 2px; padding-right: 2px;" colspan="5" align="right">রিটার্ন পরিবর্তন করুন</td>
							<td style=" padding-left: 2px; padding-right: 2px;" align="right">0.00</td>
						</tr>
										<tr>
						<td style=" padding-left: 2px; padding-right: 2px;" colspan="5" align="right">গ্রাহক বকেয়া</td>
						<td style=" padding-left: 2px; padding-right: 2px;" align="right">-78.00</td>
					</tr>
					
					<tr>
						<td colspan="6" align="center">----------আপনাকে ধন্যবাদ. আবার দর্শন!----------</td>
					</tr>

					<tr>
						<td colspan="6" align="center">
						
							<div style="display:inline-block;vertical-align:middle;line-height:16px !important;">	
								<img class="center-block" style=" width: 100%; opacity: 1.0" src="https://posmatrix.nibiz.xyz/barcode/SL0088">
							</div>
						    
						</td>
					</tr>

					</tfoot>
				</table>
				<center style="padding-top:5px;">Powered by NIBIZ SOFT</center>
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