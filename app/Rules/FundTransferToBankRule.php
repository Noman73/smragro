<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FundTransferToBankRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $frombank;
    public function __construct($fromBank)
    {
        $this->fromBank=$fromBank;
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
        if($this->fromBank==$value and ($this->fromBank!='null' and $value!='null')){
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Can't select same Bank Account";
    }
}
