<?php

namespace App\Http\Controllers\PrintView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Vinvoice;
use URL;
class PaymentViewController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
    }
    public function index($id)
    {

        $vinvoice=Vinvoice::where('id',$id)->first();
        if($vinvoice->action_type!=0 and $vinvoice->action_type!=2){
            return redirect(URL::to('admin/payment'));
        }
        $invoice=DB::select("
            SELECT voucers.id,voucers.v_inv_id,concat(account_ledgers.code,if(account_ledgers.code<>'','-',''),account_ledgers.name) name,voucers.debit,voucers.credit,voucers.comment,
            
            concat(ifnull(customers.code,''),ifnull(suppliers.code,''),ifnull(banks.code,''),ifnull(account_subledgers.code,'')) sub_code,
            
            concat(ifnull(customers.name,''),ifnull(suppliers.name,''),ifnull(banks.name,''),ifnull(account_subledgers.name,''),ifnull(employees.name,'')) sub_name
            from voucers
            inner join account_ledgers on voucers.ledger_id=account_ledgers.id
            left join account_subledgers on (account_ledgers.sub_account=1 and account_ledgers.id=account_subledgers.ledger_id and account_subledgers.id=voucers.subledger_id)
            left join suppliers on account_ledgers.sub_account=0 and account_ledgers.relation_with='suppliers' and suppliers.id=voucers.subledger_id
            left join customers on account_ledgers.sub_account=0 and account_ledgers.relation_with='customers' and customers.id=voucers.subledger_id
            left join banks on account_ledgers.sub_account=0 and account_ledgers.relation_with='banks' and voucers.subledger_id=banks.id
            left join employees on account_ledgers.sub_account=0 and account_ledgers.relation_with='employees' and voucers.subledger_id=employees.id
            where voucers.v_inv_id=:id 
            order by voucers.id
        ",['id'=>$id]);
        // dd($invoice);
        return view('backend.view_pages.payment.payment',compact('invoice','vinvoice'));
    }
    public function print($id){
        $vinvoice=Vinvoice::where('id',$id)->first();
        if($vinvoice->action_type!=0 and $vinvoice->action_type!=2){
            return redirect(URL::to('admin/payment'));
        }
        $invoice=DB::select("
            SELECT voucers.id,voucers.v_inv_id,concat(account_ledgers.code,if(account_ledgers.code<>'','-',''),account_ledgers.name) name,voucers.debit,voucers.credit,voucers.comment,
            
            concat(ifnull(customers.code,''),ifnull(suppliers.code,''),ifnull(banks.code,''),ifnull(account_subledgers.code,'')) sub_code,
            
            concat(ifnull(customers.name,''),ifnull(suppliers.name,''),ifnull(banks.name,''),ifnull(account_subledgers.name,'')) sub_name
            from voucers
            inner join account_ledgers on voucers.ledger_id=account_ledgers.id
            left join account_subledgers on (account_ledgers.sub_account=1 and account_ledgers.id=account_subledgers.ledger_id and account_subledgers.id=voucers.subledger_id)
            left join suppliers on account_ledgers.sub_account=0 and account_ledgers.relation_with='suppliers' and suppliers.id=voucers.subledger_id
            left join customers on account_ledgers.sub_account=0 and account_ledgers.relation_with='customers' and customers.id=voucers.subledger_id
            left join banks on account_ledgers.sub_account=0 and account_ledgers.relation_with='banks' and voucers.subledger_id=banks.id
            where voucers.v_inv_id=:id 
            order by voucers.id
        ",['id'=>$id]);
        return view('backend.view_pages.payment.print',compact('invoice','vinvoice'));
    }
}
