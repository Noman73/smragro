<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\AccountLedger;
use DB;
use App\Http\Traits\CashCheckTrait;
use App\Http\Traits\BalanceTrait;

class FundTransferAmmountRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    use CashCheckTrait;
    use BalanceTrait;
    private $method;
    private $bank;
    private $ammount;
    public function __construct($method,$bank)
    {
        $this->method=$method;
        $this->bank=$bank;
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
        
        if($this->method==0){
            $total_cash=$this->cashCheck();
            if(floatval($value)<=$total_cash){
                return true;
            }
        }elseif($this->method==1){
            $totalBank=$this->bankBalance($this->bank);
            if(floatval($value)<=$totalBank){
                return true;
            }
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->method==0){
            $mtd="Cash";
        }else{
            $mtd="Bank";
        }
        return 'Your '.$mtd.' Amount is lower than your target amount';
    }
}
