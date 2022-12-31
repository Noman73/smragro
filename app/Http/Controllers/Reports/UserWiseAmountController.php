<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\AccountGroup;
use App\Models\AccountLedger;
use Illuminate\Http\Request;
use DB;
class UserWiseAmountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Receive Report',['only'=>'index']);
        $this->middleware('permission:Receive Report',['only'=>'getReport']);
    }
    public function index()
    {
        return view('backend.reports.user_wise_amount.user_wise_amount');
    }
    public function getReport(Request $request)
    {
        // return $request->all();
        $from_date=strtotime($request->from_date);
        $to_date=strtotime($request->to_date);
        $bank_ledger=AccountLedger::where('name','Bank')->first()->id;
        $cash_ledger=AccountLedger::where('name','Cash')->first()->id;
        $data=DB::select("
        select userdata.name,userdata.method,userdata.id,userdata.debit,userdata.credit,userdata.total from
        
        (
        SELECT users.name,'Cash' method,users.id,ifnull(sum(voucers.debit),0.00) debit,ifnull(sum(voucers.credit),0.00) credit,ifnull(sum(voucers.debit-voucers.credit),0.00) total from users
        left join voucers on users.id=voucers.author_id
        where voucers.date>=:from_date and voucers.date<=:to_date
        and voucers.ledger_id=:cash_ledger 
        group by voucers.author_id
        UNION ALL
        SELECT users.name,'Bank' method,users.id,ifnull(sum(voucers.debit),0.00) debit,ifnull(sum(voucers.credit),0.00) credit,ifnull(sum(voucers.debit-voucers.credit),0.00) total from users
        left join voucers on users.id=voucers.author_id
        where voucers.date>=:from_date2 and voucers.date<=:to_date2
        and voucers.ledger_id=:bank_ledger
        group by voucers.author_id
        ) userdata order by userdata.id
        ",['from_date'=>$from_date,'to_date'=>$to_date,'cash_ledger'=>$cash_ledger,'bank_ledger'=>$bank_ledger,'from_date2'=>$from_date,'to_date2'=>$to_date]);
        return response()->json($data);
    }

}
