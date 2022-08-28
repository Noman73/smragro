<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Models\Voucer;
use DB;
class CustomerBalanceAnalysisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('backend.reports.customer_balance_analysis.customer_balance_analysis');
    }
    public function getReport(Request $request)
    {

        $to_date=strtotime($request->to_date);
        $customer_ledger=AccountLedger::where('name','Customer')->first()->id;
        return $data=Voucer::with(['customer','sale'])->where('ledger_id',$customer_ledger)->where('subledger_id',$request->subledger)->get();


        // $data=DB::select("
        // SELECT banks.name,sum(ifnull(voucers.debit,0)-ifnull(voucers.credit,0)) balance from banks
        // left join voucers on voucers.ledger_id=:bank_ledger and voucers.subledger_id=banks.id
        // and voucers.date<=:to_date
        // group by banks.id,voucers.subledger_id
        // ",['bank_ledger'=>$customer_ledger,'to_date'=>$to_date]);
        return response()->json($data);
    }
}
