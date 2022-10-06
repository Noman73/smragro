<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class SalesReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Sales Report',['only'=>'index']);
        $this->middleware('permission:Sales Report',['only'=>'getReport']);
    }
    public function index()
    {
        return view('backend.reports.sales_report.sales_report');
    }
    public function getReport(Request $request)
    {
        $from_date=strtotime($request->from_date);
        $to_date=strtotime($request->to_date);
        $data=DB::select("
        SELECT invoices.id,invoices.dates,customers.name,invoices.total_payable from invoices
        inner join customers on customers.id=invoices.customer_id
        where invoices.dates>=:from_date and invoices.dates<=:to_date 
        ",['from_date'=>$from_date,'to_date'=>$to_date]);
        return response()->json($data);
    }
}
