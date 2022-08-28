<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class SupplierReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('backend.reports.supplier_list.supplier_list');
    }
    public function getReport()
    {
        $data=DB::select("
        SELECT id,code,name,phone,adress,(ifnull((select sum(voucers.credit-voucers.debit) from voucers
        left join account_ledgers on voucers.ledger_id=account_ledgers.id and account_ledgers.name='Supplier'
        WHERE account_ledgers.name='Supplier' and voucers.subledger_id=suppliers.id
        ),0)) balance from suppliers
        ");
        return response()->json($data);
    }
}
