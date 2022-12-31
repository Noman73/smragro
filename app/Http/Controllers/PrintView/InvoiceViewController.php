<?php

namespace App\Http\Controllers\PrintView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
class InvoiceViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Sale Invoice Print',['only'=>'index']);
        $this->middleware('permission:Sale Invoice Print',['only'=>'print']);
        $this->middleware('permission:Sale Invoice Print',['only'=>'printInBangla']);
        $this->middleware('permission:Sale Invoice Print',['only'=>'totalChalan']);
    }
    public function index($id) 
    {
        $invoice=Invoice::with('sales','customer','pay','condition_amount','user','shipping_customer','courier','notes')->where('id',$id)->first();
        // dd($invoice);
        // dd($invoice);
        return view('backend.view_pages.invoices.invoices',compact('invoice'));
    }

    public function print($id)
    {
        $invoice=Invoice::with('sales','customer','pay','condition_amount','user','shipping_customer','courier','notes')->where('id',$id)->first();

        return view('backend.view_pages.invoices.print',compact('invoice'));
    }
    public function printInBangla($id)
    {
        $invoice=Invoice::with('sales','customer','pay','condition_amount','user','shipping_customer','courier','notes')->where('id',$id)->first();

        return view('backend.view_pages.invoices.print_bangla',compact('invoice'));
    }
    public function chalan($id)
    {
        $invoice=Invoice::with('sales','customer','pay','condition_amount','user','shipping_customer','courier','notes')->where('id',$id)->first();
        return view('backend.view_pages.invoices.chalan',compact('invoice'));
    }
    public function totalChalan($id)
    {
        $invoice=Invoice::with('sales','customer','pay','condition_amount','user','shipping_customer','courier','notes')->where('id',$id)->first();
        return view('backend.view_pages.invoices.total_chalan',compact('invoice'));
    }
    public function posPrint($id)
    {
        $invoice=Invoice::with('sales','customer','pay','condition_amount','user','shipping_customer','courier','notes')->where('id',$id)->first();

        return view('backend.view_pages.invoices.pos-print',compact('invoice'));
    }

}
