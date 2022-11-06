<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\AccountLedger;
class JournalSubledgerRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $ledger;
    public $row;
    public function __construct($ledger)
    {
        $this->ledger=$ledger;
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
        $i=0;
        foreach($this->ledger as $ledger){
            $ledger=AccountLedger::where('id',$ledger)->first();
            if($ledger->relation_with!=null or $ledger->sub_account==1){
                if($value[$i]=='null'){
                    $this->row=$i;
                    return false;
                }else{
                    $cond= true;
                }
            }else{
                $cond= true;
            }
            $i=$i+1;
        }
        return $cond;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Row no '.($this->row+1).' sub ledger is required.';
    }
}
