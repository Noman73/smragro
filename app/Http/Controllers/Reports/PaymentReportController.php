<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class PaymentReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('backend.reports.payment_report.payment_report');
    }
    public function getReport(Request $request)
    {
        $from_date=strtotime($request->from_date);
        $to_date=strtotime($request->to_date);
        $data=DB::select("
        SELECT id,date,total,note from vinvoices where date>=:from_date and date<=:to_date and (action_type=2 or action_type=0)
        ",['from_date'=>$from_date,'to_date'=>$to_date]);
        return response()->json($data);
    }
}
