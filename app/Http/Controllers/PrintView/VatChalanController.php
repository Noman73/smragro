<?php

namespace App\Http\Controllers\PrintView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
class VatChalanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Sale Invoice Print',['only'=>'index']);
        $this->middleware('permission:Sale Invoice Print',['only'=>'print']);
        
    }
    public function print($id){
        $invoice=Invoice::with('sales','customer','pay','condition_amount','user','shipping_customer','courier','notes','road_chalan')->where('id',$id)->first();
        return view('backend.view_pages.invoices.vat_chalan.print',compact('invoice'));
    }
}
