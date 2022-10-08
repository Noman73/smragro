<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class PurchaseReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Purchase Report',['only'=>'index']);
        $this->middleware('permission:Purchase Report',['only'=>'getReport']);
    }
    public function index()
    {
        return view('backend.reports.purchase_report.purchase_report');
    }
    public function getReport(Request $request)
    {
        $from_date=strtotime($request->from_date);
        $to_date=strtotime($request->to_date);
        $data=DB::select("
        SELECT p_invoices.id,p_invoices.dates,suppliers.name,p_invoices.total_payable from p_invoices
        left join suppliers on suppliers.id=p_invoices.supplier_id
        where p_invoices.dates>=:from_date and p_invoices.dates<=:to_date 
        ",['from_date'=>$from_date,'to_date'=>$to_date]);
        return response()->json($data);
    }
}
