<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\BalanceTrait;
class HelperController extends Controller
{
    use BalanceTrait;
    public function getCashBalance()
    {
        return $this->cashBalance();
    }


    public function getBankBalance($bank_id)
    {
        return $this->bankBalance($bank_id);
    }
}
