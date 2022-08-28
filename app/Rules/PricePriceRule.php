<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PricePriceRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $msg;
    public function __construct()
    {

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
        info($value);
        $length=count($value);
        for($i = 0; $i < $length; $i++){
            // info($value[$i]);
            if($value[$i]=='' ||  $value[$i]<=0){
                $this->msg="The table row ".($i+1)." price is required";
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
        return $this->msg;
    }
}
