<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\AccountLedger;
class BankBalanceReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Bank Balance Report',['only'=>'index']);
        $this->middleware('permission:Bank Balance Report',['only'=>'getReport']);
    }
    public function index()
    {
        return view('backend.reports.bank_balance.bank_balance');
    }
    public function getReport(Request $request)
    {
        $to_date=strtotime($request->to_date);
        $bank_ledger=AccountLedger::where('name','Bank')->first()->id;
        $data=DB::select("
        SELECT banks.name,sum(ifnull(voucers.debit,0)-ifnull(voucers.credit,0)) balance from banks
        left join voucers on voucers.ledger_id=:bank_ledger and voucers.subledger_id=banks.id
        and voucers.date<=:to_date
        group by banks.id,voucers.subledger_id
        ",['bank_ledger'=>$bank_ledger,'to_date'=>$to_date]);
        return response()->json($data);
    }
}
