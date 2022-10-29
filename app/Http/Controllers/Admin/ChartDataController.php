<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
class ChartDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getYearlyInvoiceData()
    {
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date("01-M-Y 00:00:00", strtotime( date( 'Y-m-01' )." -$i months"));
        }
        $months=array_reverse($months);
        foreach($months as $month){
            $strtotime=strtotime($month);
            $strtotimeLast=strtotime(date('t-m-Y 23:59:59',strtotime($month)));
            $data[date('M Y',strtotime($month))]=number_format(Invoice::where('dates','>=',$strtotime)->where('dates','<=',$strtotimeLast)->sum('total_payable'),2);
        }
        return response()->json($data);
    }
}
