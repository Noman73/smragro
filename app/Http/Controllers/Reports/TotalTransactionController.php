<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class TotalTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Total Transaction Report',['only'=>'index']);
        $this->middleware('permission:Total Transaction Report',['only'=>'getReport']);
    }
    public function index()
    {
        return view('backend.reports.total_transaction.total_transaction');
    }
    public function getReport(Request $request)
    {

        $from_date=strtotime($request->from_date);
        $to_date=strtotime($request->to_date);
        $data=DB::select("
        SELECT id,date,'Journal' type,ifnull(note,'') as note,total from invoice_journals
        where date>=:from_date and date<=:to_date
        union all
        SELECT id,date,
        CASE
            WHEN action_type=0 THEN 'Payment'
            WHEN action_type=1 THEN 'Receive'
            WHEN action_type=2 THEN 'Receive'
            WHEN action_type=3 THEN 'Payment'
        END,
        ifnull(note,''),
        total from vinvoices
        where date>=:from_date2 and date<=:to_date2
        union all
        select id,dates,'Sales',ifnull(note,''),total_payable from invoices where (action_id=0 or action_id=1 or action_id=2)
        union all 
        select id,dates,'Purchase',ifnull(note,''),total_payable from p_invoices where (action_id=0)
        order by date asc
        ",['from_date'=>$from_date,'to_date'=>$to_date,'from_date2'=>$from_date,'to_date2'=>$to_date]);
        return response()->json($data);
    }
}
