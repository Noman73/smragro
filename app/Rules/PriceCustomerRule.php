<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Price;
class PriceCustomerRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $products;
    private $msg;
    public function __construct($products)
    {
        $this->products=$products;
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
        $length=count($this->products);
        for($i = 0; $i < $length; $i++){
            $count=Price::where('product_id',$this->products[$i])->where('customer_id',$value)->count();
            if($count>0){
                $this->msg="The Customer and Product Row ".($i+1)." already Existed";
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
