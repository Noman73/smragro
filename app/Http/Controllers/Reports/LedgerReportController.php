<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\AccountLedger;
use Illuminate\Http\Request;
use Validator;
use DB;
class LedgerReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Ledger Report',['only'=>'index']);
        $this->middleware('permission:Ledger Report',['only'=>'getReport']);
    }
    public function index()
    {
        return view('backend.reports.ledger.account_ledger.ledger');
    }
    public function generateReport(Request $request)
    {
        // return $request->all();
        $data=$request->all();
        if($data['subledger']=='null'){
            $data['subledger']=null;
        }

        $validator=Validator::make($data,[
            'from_date'=>"required|max:20|date_format:d-m-Y",
            'to_date'=>"required|max:20|date_format:d-m-Y",
            'ledger'=>"required|max:20",
            'subledger'=>"nullable|max:20",
        ]);
        if($validator->passes()){

            $from_date=strtotime($request->from_date);
            $to_date=strtotime($request->to_date);
            // account class
            $class=DB::select("select classes.name,classes.class_type from account_ledgers
                    inner join account_groups on account_groups.id=account_ledgers.group_id 
                    inner join classes on classes.id=account_groups.class_id
                    where account_ledgers.id=:ledger_id;
            ",['ledger_id'=>$data['ledger']])[0];
            $cond=($class->class_type==1 || $class->class_type==4 ? "dmc": "cmd" );

            if($data['ledger']==26){
            // condition customer only customers type 2 is condition customer
            $previous=DB::select("
                SELECT if('".$cond."'='dmc',sum(debit)-sum(credit),sum(credit)-sum(debit)) balance from voucers
                inner join customers on customers.id=voucers.subledger_id
                where ledger_id=:ledger_id and customers.type=2 and voucers.date<:from_date
            ",['ledger_id'=>AccountLedger::where('name','Customer')->first()->id,'from_date'=>$from_date]);
            $arr= DB::select("
            SELECT voucer.id,voucer.v_inv_id,voucer.journal_inv_id,voucer.invoice_id,voucer.pinvoice_id,voucer.date,voucer.debit,voucer.credit,voucer.transaction_name,voucer.comment,voucer.created_at from
            (
                    SELECT voucers.id,voucers.v_inv_id,voucers.journal_inv_id,voucers.invoice_id,voucers.pinvoice_id,voucers.date,voucers.debit,voucers.credit,voucers.transaction_name,voucers.comment,voucers.created_at from voucers
                    inner join customers on customers.id=voucers.subledger_id
                     where voucers.ledger_id=:ledger_id and customers.type=2 and voucers.date>=:from_date and voucers.date<=:to_date
                    UNION ALL
                    SELECT 0,'','','','','',0.00,0.00,'P/F','',''
            ) voucer
            order by voucer.date,voucer.id
                ",['ledger_id'=>AccountLedger::where('name','Customer')->first()->id,'from_date'=>$from_date,'to_date'=>$to_date]);
            }elseif($data['ledger']!='null' and $data['subledger']==null){
            // without subledger
            $previous=DB::select("
                SELECT if('".$cond."'='dmc',sum(debit)-sum(credit),sum(credit)-sum(debit)) balance from voucers where ledger_id=:ledger_id and date<:from_date
            ",['ledger_id'=>$data['ledger'],'from_date'=>$from_date]);
            $arr= DB::select("
            SELECT voucer.id,voucer.v_inv_id,voucer.journal_inv_id,voucer.invoice_id,voucer.pinvoice_id,voucer.date,voucer.debit,voucer.credit,voucer.transaction_name,voucer.comment,voucer.created_at from
            (
                    SELECT voucers.id,voucers.v_inv_id,voucers.journal_inv_id,voucers.invoice_id,voucers.pinvoice_id,voucers.date,voucers.debit,voucers.credit,voucers.transaction_name,voucers.comment,voucers.created_at from voucers
                    
                     where voucers.ledger_id=:ledger_id and date>=:from_date and date<=:to_date
                    UNION ALL
                    SELECT 0,'','','','','',0.00,0.00,'P/F','',''
            ) voucer
            order by voucer.date,voucer.id
                ",['ledger_id'=>$data['ledger'],'from_date'=>$from_date,'to_date'=>$to_date]);
            }elseif($data['ledger']!='null' and $data['subledger']!=null){
                // with subledger
                $previous=DB::select("
                SELECT if('".$cond."'='dmc',sum(debit)-sum(credit),sum(credit)-sum(debit)) balance from voucers where ledger_id=:ledger_id and subledger_id=:subledger and date<:from_date 
            ",['ledger_id'=>$data['ledger'],'subledger'=>$data['subledger'],'from_date'=>$from_date]);
            $arr= DB::select("
                    SELECT voucer.id,voucer.invoice_id,voucer.v_inv_id,voucer.pinvoice_id,voucer.journal_inv_id,voucer.date,voucer.debit,voucer.credit,voucer.transaction_name,voucer.comment,voucer.created_at from
                    (
                        SELECT voucers.id,voucers.invoice_id,voucers.v_inv_id,voucers.pinvoice_id,voucers.journal_inv_id,voucers.date,voucers.debit,voucers.credit,voucers.transaction_name,voucers.comment,voucers.created_at from voucers 
                        where voucers.ledger_id=:ledger_id and voucers.subledger_id=:subledger and date>=:from_date and date<=:to_date
                        UNION ALL 
                        SELECT 0,'','','','','',0.00,0.00,'P/F',null,''
                    ) voucer
                    order by voucer.date,voucer.id
                ",['ledger_id'=>$data['ledger'],'subledger'=>$data['subledger'],'from_date'=>$from_date,'to_date'=>$to_date]);
            }
            return response()->json([$arr,$previous,$class]);
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }
}