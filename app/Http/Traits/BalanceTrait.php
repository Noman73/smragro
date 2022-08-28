<?php 

namespace App\Http\Traits;
use App\Models\AccountLedger;
use App\Models\Voucer;
use App\Models\CreditSetup;
use DB;
use PhpParser\Node\Expr\Isset_;

trait BalanceTrait{


	public function cashBalance()
	{
		$cash=AccountLedger::where('name',"Cash")->first();
		$dabit=Voucer::where('ledger_id',$cash->id)->sum('debit');
		$credit=Voucer::where('ledger_id',$cash->id)->sum('credit');
		return $total=floatval($dabit-$credit);
	}

    public function bankBalance($id)
    {
		$cash=AccountLedger::where('name',"Bank")->first();
        $dabit=Voucer::where('ledger_id',$cash->id)->where('subledger_id',$id)->sum('debit');
		$credit=Voucer::where('ledger_id',$cash->id)->where('subledger_id',$id)->sum('credit');
		return $total=floatval($dabit-$credit);
    }
	static function customerBalance($id)
	{
		$total=DB::select("select sum(voucers.debit-voucers.credit) total from voucers 
		left join account_ledgers on account_ledgers.id=voucers.ledger_id where  account_ledgers.name='Customer' and voucers.subledger_id=:id",['id'=>$id])[0]->total;
		return $total;
	}

	static function previousBalance($id,$invoice_id)
	{
		return $total=DB::select("select ifnull(sum(voucers.debit-voucers.credit),0) total from voucers 
		left join account_ledgers on account_ledgers.id=voucers.ledger_id where  account_ledgers.name='Customer' and subledger_id=:id and voucers.id<:invoice_id


		
		 ",['id'=>$id,'invoice_id'=>$invoice_id])[0]->total;
	}
	static function previousBalanceSupplier($id,$invoice_id)
	{
		return $total=DB::select("select ifnull(sum(voucers.debit-voucers.credit),0) total from voucers 
		left join account_ledgers on account_ledgers.id=voucers.ledger_id where  account_ledgers.name='Supplier' and subledger_id=:id and voucers.id<:invoice_id
		 ",['id'=>$id,'invoice_id'=>$invoice_id])[0]->total;
	}

	static function checkCreditLimit($total_invoice_amount,$customer_id)
	{

		$credit=CreditSetup::where('customer_id',$customer_id)->first();
		$total= floatval(self::customerBalance($customer_id))+floatval($total_invoice_amount);
		$total_balance_needed=$total-floatval((isset($credit->amount) ? $credit->amount : 0));
		if($credit==null){
			$message=['status'=>true,'message'=>'credit not setup with this customer'];
		}elseif($total<=$credit->amount){
			$message=['status'=>true,'message'=>'amount is lower than credit limit'];
		}else{
			$message=['status'=>false,'message'=>'this amount exceeded your credit limit BDT. '.number_format($credit,2) ];
		}
		return $message;
	}
}