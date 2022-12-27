<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\AccountLedger;
class AccountLedgerRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $ledger_id;
    public function __construct($ledger_id)
    {
        $this->ledger_id=$ledger_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $fixed_ledger=['Customer','Supplier','Bank','Cash','Bank Loan','Sales','Cost of Goods Sold - Retail','Inventory','Condition Customer','Purchase','Staff Salary','Vat','	Transport Income','Employee Salary Account','Employee Loan'];
        $ledger=AccountLedger::where('id',$this->ledger_id)->first();
        $exist=in_array($ledger->name,$fixed_ledger);
        if($exist){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The ledger name cannot be change';
    }
}
