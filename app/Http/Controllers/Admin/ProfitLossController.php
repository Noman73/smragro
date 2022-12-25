<?php

<<<<<<< HEAD
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class ProfitLossController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function profitLossAccount(Request $request)
    {
        $to_date=$request->to_date;
        $gross_profit = DB::select("
        SELECT 
        sale.product_name,cast(sale.qantity as decimal(20,2)) quantity,cast(sale.price as decimal(20,2)) sale_rate,cast(purchase.price as decimal(20,2)) purchase_rate,cast(sale.qantity*sale.price as decimal(20,2)) as sale_price,cast(sale.qantity*purchase.price as decimal(20,2)) as buy_price,cast((sale.qantity*sale.price)-(sale.qantity*purchase.price) as decimal(20,2)) as profit
        from (
        select sales.dates,products.product_name,sales.product_id,sum(sales.deb_qantity) qantity,sum(sales.price*(sales.deb_qantity-sales.cred_qantity)-sales.price*(sales.deb_qantity-sales.cred_qantity)*sales.discount/100)/sum(sales.deb_qantity-sales.cred_qantity) price from sales 
        inner join products on products.id=sales.product_id
        where sales.dates >=:fromDate and sales.dates <= :toDate and (action_id=0 or action_id=3)
        group by sales.product_id 
        ) sale
        left join (
        select products.product_name,purchases.product_id,sum(purchases.deb_qantity) qantity,sum(purchases.price*purchases.deb_qantity) pt_price,sum(purchases.price*purchases.deb_qantity)/sum(purchases.deb_qantity) price from purchases 
        inner join products on products.id=purchases.product_id
        group by purchases.product_id
        ) purchase on purchase.product_id=sale.product_id 
=======
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfitLossController extends Controller
{
    public  function __construct()
    {
       $this->middleware('auth'); 
    }

    public function ProfitLossAccount()
    {
        $gross_profit=DB::select("
            select sum(purchases.deb_qantity-purchases.cred_qantity) purchase_qty,
>>>>>>> smragro
        ");
    }
}
