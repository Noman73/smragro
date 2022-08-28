<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($invoice_id){

        $data=DB::select("
            Select invoices.id,invoices.shipping_id,invoices.payment_id,invoices.dates,invoices.discount,invoices.chalan_no,
        ");
        return view('backend.view_pages.invoices.invoice');
    }
}
