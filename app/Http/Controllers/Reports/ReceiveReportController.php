<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class ReceiveReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Receive Report',['only'=>'index']);
        $this->middleware('permission:Receive Report',['only'=>'getReport']);
    }
    public function index()
    {
        return view('backend.reports.receive_report.receive_report');
    }
    public function getReport(Request $request)
    {
        $from_date=strtotime($request->from_date);
        $to_date=strtotime($request->to_date);
        $data=DB::select("
        SELECT id,date,total,ifnull(note,'') note from vinvoices where date>=:from_date and date<=:to_date and (action_type=3 or action_type=1)
        ",['from_date'=>$from_date,'to_date'=>$to_date]);
        return response()->json($data);
    }
}
