<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\CustomerMultiply;
class CustomerMultiplyRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $customer_id;
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
        $count=CustomerMultiply::where('customer_id',$this->customer_id)->where('brand_id',$value)->count();
        if($count>0){
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
        return 'this brand for customer already exist';
    }
}
