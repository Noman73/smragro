<?php 

namespace App\Http\Traits;
use App\Models\AccountLedger;
use App\Models\Voucer;
trait CashCheckTrait{


	public function cashCheck()
	{
		$cash=AccountLedger::where('name',"Cash")->first();

		$dabit=Voucer::where('ledger_id',$cash->id)->sum('debit');
		$credit=Voucer::where('ledger_id',$cash->id)->sum('credit');
		return $total=floatval($dabit-$credit);
	}
}