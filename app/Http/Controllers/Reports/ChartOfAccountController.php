<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class ChartOfAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Chart Of Account Report',['only'=>'index']);
        $this->middleware('permission:Chart Of Account Report',['only'=>'getReport']);
    }
    public function index()
    {
    
        return view('backend.reports.chart_of_account.chart_of_accounts');
    }
    public function getReport(Request $request)
    {
        $query="SELECT classes.class_type,classes.name class_name,account_groups.name group_name,account_ledgers.name,account_ledgers.code,ifnull(sum(voucers.debit),0) debit,ifnull(sum(voucers.credit),0.00) credit  from account_ledgers 
        left join voucers on voucers.ledger_id=account_ledgers.id
        inner join account_groups on account_ledgers.group_id=account_groups.id
        inner join classes on account_groups.class_id=classes.id
        group by account_ledgers.id,account_ledgers.name,account_groups.name,classes.name
        order by classes.id,account_groups.id,account_ledgers.id
       ";
        $data=DB::select($query);
        return response()->json($data);
    }
}
