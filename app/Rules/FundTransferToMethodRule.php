<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FundTransferToMethodRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $fromMethod;
    public function __construct($fromMethod)
    {
        $this->fromMethod=$fromMethod;
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
        if($this->fromMethod==0){
            if($this->fromMethod==$value){
                return false;
            }
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
        return 'same method conflict.';
    }
}
