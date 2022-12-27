<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfitLossController extends Controller
{
    public  function __construct()
    {
       $this->middleware('auth'); 
    }

    public function getReport(Request $request)
    {
        $from_date=strtotime($request->from_date);
        $to_date=strtotime($request->to_date);
        $gross_profit=DB::select("
        select 'Gross Profit' name, cast(sum(gross_profit.gross_profit) as decimal(20,2)) total from 
        (
        select product.id,product.part_id,product.name, (sales.deb_qantity-sales.cred_qantity) sale_qty,product.qty,product.total,(product.total/product.qty) buy_price,
        ((sales.deb_qantity-sales.cred_qantity)*sales.price)-((sales.deb_qantity-sales.cred_qantity)*(product.total/product.qty)) gross_profit
        from sales
        
        inner join 
        (
        select 
        products.name,
        products.id,
        products.part_id,
        sum(purchases.deb_qantity-purchases.cred_qantity) qty,
        purchases.price,
        sum((purchases.deb_qantity-purchases.cred_qantity)*purchases.price) total
        from products
        left join purchases on purchases.product_id=products.id
        group by products.id
        
        ) product on sales.product_id=product.id where sales.dates>=:from_date and sales.dates<=:to_date
        ) gross_profit
        ",['from_date'=>$from_date,'to_date'=>$to_date]);

        $expenses=DB::select("
        select account_ledgers.name,ifnull(sum(voucers.debit-voucers.credit),0.00) total from account_ledgers
        inner join account_groups on account_groups.id=account_ledgers.group_id and account_groups.name='Indirect Expenses'
        left join voucers on voucers.ledger_id=account_ledgers.id
        where voucers.date>=:from_date and voucers.date<=to_date
        group by account_ledgers.id
        ",['from_date'=>$from_date,'to_date'=>$to_date]);

        return response()->json(['gross_profit'=>$gross_profit,'expenses'=>$expenses]);
    }
}