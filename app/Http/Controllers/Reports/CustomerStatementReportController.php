<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\AccountLedger;
use DB;
class CustomerStatementReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Customer Statement View',['only'=>'index']);
        $this->middleware('permission:Customer Statement View',['only'=>'getReport']);
    }
    public function index()
    {
        return view('backend.reports.customer_statement.customer_statement');
    }
    public function generateReport(Request $request)
    {
        // return $request->all();
        $data=$request->all();
    
        $validator=Validator::make($data,[
            'from_date'=>"required|max:20|date_format:d-m-Y",
            'to_date'=>"required|max:20|date_format:d-m-Y",
            'subledger'=>"nullable|max:20",
        ]);
        if($validator->passes()){

            $from_date=strtotime($request->from_date);
            $to_date=strtotime($request->to_date);
            // account class
           $customer_ledger=AccountLedger::where('name','Customer')->first()->id;
            $class=DB::select("select classes.name,classes.class_type from account_ledgers
                    inner join account_groups on account_groups.id=account_ledgers.group_id 
                    inner join classes on classes.id=account_groups.class_id
                    where account_ledgers.id=:ledger_id;
            ",['ledger_id'=>$customer_ledger])[0];

            
            if($request->subledger!=null){

            
            $previous=DB::select("
                SELECT sum(debit)-sum(credit) balance from voucers where ledger_id=:ledger_id and subledger_id=:subledger and date<:from_date
            ",['ledger_id'=>$customer_ledger,'subledger'=>$request->subledger,'from_date'=>$from_date]);
            $arr= DB::select("
            SELECT voucer.id,voucer.invoice_id,voucer.v_inv_id,voucer.pinvoice_id,voucer.journal_inv_id,voucer.date,voucer.debit,voucer.credit,voucer.transaction_name,voucer.comment,voucer.created_at from
            (
                    SELECT voucers.id,voucers.invoice_id,voucers.v_inv_id,voucers.pinvoice_id,voucers.journal_inv_id,voucers.date,voucers.debit,voucers.credit,voucers.transaction_name,voucers.comment,voucers.created_at from voucers
                    
                     where voucers.ledger_id=:ledger_id and voucers.subledger_id=:subledger and date>=:from_date and date<=:to_date
                    UNION ALL
                    SELECT 0,'','','','','',0.00,0.00,'P/F','',''
            ) voucer
            order by voucer.date,voucer.id
                ",['ledger_id'=>$customer_ledger,'subledger'=>$request->subledger,'from_date'=>$from_date,'to_date'=>$to_date]);
            
            return response()->json([$arr,$previous,$class]);
            }
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }
}
