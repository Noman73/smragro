<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfitLossController extends Controller
{
    public  function __construct()
    {
       $this->middleware('auth'); 
    }

    public function ProfitLossAccount()
    {
        $gross_profit=DB::select("
            select sum(purchases.deb_qantity-purchases.cred_qantity) purchase_qty,
        ");
    }
}
