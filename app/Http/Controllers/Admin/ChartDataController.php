<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucer;
use App\Models\Invoice;
use App\Models\AccountLedger;
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
            $data[date('M Y',strtotime($month))]=number_format(Invoice::where('dates','>=',$strtotime)->where('dates','<=',$strtotimeLast)->sum('total_payable'), 2, '.', '');
        }
        return response()->json($data);
    }
    public function last30DaysReceivePayment()
    {
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date("01-M-Y 00:00:00", strtotime( date( 'Y-m-01' )." -$i months"));
        }
        $months=array_reverse($months);
        $bank_ledger=AccountLedger::where('name','Bank')->first()->id;
        $cash_ledger=AccountLedger::where('name','Cash')->first()->id;
        foreach($months as $month){
            $strtotime=strtotime($month);
            $strtotimeLast=strtotime(date('t-m-Y 23:59:59',strtotime($month)));
            $receive[date('M Y',strtotime($month))]=number_format(Voucer::where('date','>=',$strtotime)->where('date','<=',$strtotimeLast)->where(function($query) use ($bank_ledger,$cash_ledger){
                $query->where('ledger_id',$bank_ledger)->orWhere('ledger_id',$cash_ledger);
            })
            ->sum('debit'), 2, '.', '');
            $payment[date('M Y',strtotime($month))]=number_format(Voucer::where('date','>=',$strtotime)->where('date','<=',$strtotimeLast)->where(function($query) use ($bank_ledger,$cash_ledger){
                $query->where('ledger_id',$bank_ledger)->orWhere('ledger_id',$cash_ledger);
            })
            ->sum('credit'), 2, '.', '');
        }
        return response()->json(['receive'=>$receive,'payment'=>$payment]);
    }

}
