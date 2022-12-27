<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class ProfitLossController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Super-Admin');
    }
    public function index()
    {
        return view('backend.reports.profit_loss.profit_loss');
    }
    public function getReport(Request $request)
    {
        $from_date=strtotime($request->from_date);
        $to_date=strtotime($request->to_date);
        // $gross_profit=DB::select("
        // select 'Gross Profit' name, cast(sum(gross_profit.gross_profit) as decimal(20,2)) total from 
        // (
        // select product.id,product.part_id,product.name, (sales.deb_qantity-sales.cred_qantity) sale_qty,product.qty,product.total,(product.total/product.qty) buy_price,
        // ((sales.deb_qantity-sales.cred_qantity)*sales.price)-((sales.deb_qantity-sales.cred_qantity)*(product.total/product.qty)) gross_profit
        // from sales
        // inner join 
        // (
        // select 
        // products.name,
        // products.id,
        // products.part_id,
        // sum(purchases.deb_qantity-purchases.cred_qantity) qty,
        // purchases.price,
        // sum((purchases.deb_qantity-purchases.cred_qantity)*purchases.price) total
        // from products
        // left join purchases on purchases.product_id=products.id
        // group by products.id
        
        // ) product on sales.product_id=product.id where sales.dates>=:from_date and sales.dates<=:to_date
        // ) gross_profit
        // ",['from_date'=>$from_date,'to_date'=>$to_date]);
        $opening_stock=DB::select("
        select 'Opening stock' name,ifnull(sum(stocks.stock*stocks.buy_price),0.00) total from 
        (
        select product.id,product.name, (sales.deb_qantity-sales.cred_qantity) sale_qty,product.qty,product.total,(product.total/product.qty) buy_price,
        product.qty-(sales.deb_qantity-sales.cred_qantity) stock
        from purchases
        left join sales on sales.product_id=purchases.product_id
        inner join 
        (
        select 
        products.name,
        products.id,
        sum(purchases.deb_qantity-purchases.cred_qantity) qty,
        purchases.price,
        sum((purchases.deb_qantity-purchases.cred_qantity)*purchases.price) total
        from products
        left join purchases on purchases.product_id=products.id
        where purchases.dates<:from_date2 group by products.id
        ) product on purchases.product_id=product.id
        where sales.dates < :from_date  group by purchases.product_id
        ) stocks
        ",['from_date'=>$from_date,'from_date2'=>$from_date])[0]->total;

        $closing_stock=DB::select("
        select 'closing stock' name,sum(stocks.stock*stocks.buy_price) total from 
        (
        select product.id,product.name, (sales.deb_qantity-sales.cred_qantity) sale_qty,product.qty,product.total,(product.total/product.qty) buy_price,
        product.qty-(sales.deb_qantity-sales.cred_qantity) stock
        from purchases
        left join sales on sales.product_id=purchases.product_id
        inner join 
        (
        select 
        products.name,
        products.id,
        sum(purchases.deb_qantity-purchases.cred_qantity) qty,
        purchases.price,
        sum((purchases.deb_qantity-purchases.cred_qantity)*purchases.price) total
        from products
        left join purchases on purchases.product_id=products.id
        group by products.id
        ) product on purchases.product_id=product.id  group by purchases.product_id
        ) stocks
        ");


        $expenses=DB::select("
        select account_ledgers.name,ifnull(sum(voucers.debit-voucers.credit),0.00) total from account_ledgers
        inner join account_groups on account_groups.id=account_ledgers.group_id and account_groups.name='Indirect Expenses'
        left join voucers on voucers.ledger_id=account_ledgers.id
        and voucers.date>=:from_date and voucers.date<=:to_date
        group by account_ledgers.id
        ",['from_date'=>$from_date,'to_date'=>$to_date]);

        $purchase=DB::select("
        select ifnull(sum(voucers.debit-voucers.credit),0.00) total from account_ledgers 
        left join voucers on voucers.ledger_id=account_ledgers.id
        where account_ledgers.name='Purchase' and voucers.date >= :from_date and voucers.date<=:to_date;
        ",['from_date'=>$from_date,'to_date'=>$to_date]);
        $sales=DB::select("
        select ifnull(sum(voucers.credit-voucers.debit),0.00) total from account_ledgers 
        left join voucers on voucers.ledger_id=account_ledgers.id
        where account_ledgers.name='Sales' and voucers.date >= :from_date and voucers.date<=:to_date;
        ",['from_date'=>$from_date,'to_date'=>$to_date]);
        $indirect_income=DB::select("
        select account_ledgers.name,sum(voucers.debit-voucers.credit) total from account_ledgers
        left join voucers on voucers.ledger_id=account_ledgers.id
        where account_ledgers.name='Other Revenue' and voucers.date >= :from_date and voucers.date<=:to_date
        group by account_ledgers.id
        ",['from_date'=>$from_date,'to_date'=>$to_date]);


        return response()->json(['closing_stock'=>$closing_stock,'expenses'=>$expenses,'purchase'=>$purchase,'sales'=>$sales,'indirect_income'=>$indirect_income,'opening_stock'=>$opening_stock]);
    }
}
