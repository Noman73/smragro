<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Http\Traits\BalanceTrait;
class CheckCreditLimit implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    use BalanceTrait;
    public $customer_id;
    public $msg;
    public function __construct($customer_id)
    {
        $this->customer_id=$customer_id;
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

        if($this->customer_id!=null){
            $check=BalanceTrait::checkCreditLimit($value,$this->customer_id);
            info($check['status']);
            if($check['status']){
                return true;
            }else{
                $this->msg=$check['message'];
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
