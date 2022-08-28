<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class TrialBalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('backend.reports.trial_balance.trial_balance');
    }

    public function getReport(Request $request)
    {
        // return $request->all();
        
        return DB::select("
           select classes.class_type,classes.name class_name,account_ledgers.code,account_ledgers.name,ifnull(sum(voucers.debit),0) debit,ifnull(sum(voucers.credit),0) credit from account_ledgers
           left join voucers on voucers.ledger_id=account_ledgers.id
           inner join account_groups on account_groups.id=account_ledgers.group_id
           inner join classes on classes.id=account_groups.class_id
           group by account_ledgers.id 
        ");
    }
}
