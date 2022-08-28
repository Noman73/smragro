<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\AccountLedger;
class CashInHandReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('backend.reports.cashinhand.cashinhand');
    }
    public function getReport(Request $request)
    {
        $to_date=strtotime($request->to_date);
        $bank_ledger=AccountLedger::where('name','Bank')->first()->id;
        $cash_ledger=AccountLedger::where('name','Cash')->first()->id;
        $data=DB::select("
        SELECT banks.id,banks.name,sum(ifnull(voucers.debit,0)-ifnull(voucers.credit,0)) balance from banks
        left join voucers on voucers.ledger_id=:bank_ledger and voucers.subledger_id=banks.id
        and voucers.date<=:to_date
        group by banks.id,voucers.subledger_id
        union all
        select 0,'Cash', sum(ifnull(voucers.debit,0)-ifnull(voucers.credit,0)) from voucers
        where voucers.ledger_id=:cash_ledger and voucers.date<=:to_date2
        order by id asc
        ",['bank_ledger'=>$bank_ledger,'cash_ledger'=>$cash_ledger,'to_date'=>$to_date,'to_date2'=>$to_date]);
        return response()->json($data);
    }
}
