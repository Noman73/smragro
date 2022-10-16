<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\AccountLedger;
class StockValueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Inventory Report',['only'=>'index']);
        $this->middleware('permission:Inventory Report',['only'=>'getReport']);
    }
    public function index()
    {
        return view('backend.reports.stock_value.inventory_report');
    }
    public function getReport(Request $request)
    {
        // return $request->all();
        $from_date=strtotime($request->from_date);
        $to_date=strtotime($request->to_date);
        $supp_ledger=AccountLedger::where('name','Supplier')->first()->id;
        $supp_due=DB::select("select sum(debit-credit) total from voucers where ledger_id=:ledger_id and date>=:from_date and date <=:to_date",['ledger_id'=>$supp_ledger,'from_date'=>$from_date,'to_date'=>$to_date]);
        $data=DB::select("
        select inv_table.sale_type,inv_table.stock_in,inv_table.price from
        (
        select 
        nvl2(purchases.supplier_id,'not null','null') sale_type,
        products.name,
        sum(purchases.deb_qantity-purchases.cred_qantity) stock_in,
        cast((sum(purchases.price)/sum(purchases.deb_qantity-purchases.cred_qantity)) as decimal(20,2)) price
        from purchases
        inner join products on products.id=purchases.product_id
        where purchases.dates>=:from_date and purchases.dates <=:to_date 
        group by purchases.product_id,nvl2(purchases.supplier_id,'not null','null') order by purchases.dates

        ) inv_table 
        ",['from_date'=>$from_date,'to_date'=>$to_date]);
        return response()->json(['data'=>$data,'supp_due'=>$supp_due]);
    }
}
