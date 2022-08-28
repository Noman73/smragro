<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Http\Traits\CashCheckTrait;
class CashCheckRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    use CashCheckTrait;

    private $totalCash;
    private $value;
    private $ledger_id;
    public function __construct($ledger)
    {
        $this->ledger_id=$ledger;
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
        $total=0;
        foreach($value as $val){
            $total=$total+floatval($val);
        }
        $totalCash=$this->cashCheck();
        $this->totalCash=$totalCash;
        $this->value=$total;
        if($totalCash>=$this->value){
            return true;
        }else{
            return false;
        }
        
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Insufficient Cash. You  need +'.number_format(floatval($this->value)-floatval($this->totalCash),2,'.',',')."৳ for this transaction";
    }
}
