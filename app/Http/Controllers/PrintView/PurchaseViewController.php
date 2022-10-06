<?php

namespace App\Http\Controllers\PrintView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PInvoice;
class PurchaseViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Purchase Invoice Print',['only'=>'index']);
        $this->middleware('permission:Purchase Invoice Print',['only'=>'print']);
    }
    public function index($id) 
    {
        $invoice=PInvoice::with('purchase','supplier')->where('id',$id)->first();
        // dd($invoice);
        // dd($invoice);
        return view('backend.view_pages.purchases.invoice',compact('invoice'));
    }

    public function print($id)
    {
        $invoice=PInvoice::with('purchase','supplier')->where('id',$id)->first();

        return view('backend.view_pages.purchases.print',compact('invoice'));
    }
}
