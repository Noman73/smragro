<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class CustomerReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Customer List Report',['only'=>'index']);
        $this->middleware('permission:Customer List Report',['only'=>'getReport']);
    }
    public function index()
    {
        return view('backend.reports.customer_list.customer_list');
    }
    public function getReport()
    {
        $data=DB::select("
        SELECT id,code,name,phone,adress,(ifnull((select sum(voucers.debit-voucers.credit) from voucers
        left join account_ledgers on voucers.ledger_id=account_ledgers.id and account_ledgers.name='Customer'
        WHERE account_ledgers.name='Customer' and voucers.subledger_id=customers.id
        ),0)) balance from customers
        ");
        return response()->json($data);
    }
}
