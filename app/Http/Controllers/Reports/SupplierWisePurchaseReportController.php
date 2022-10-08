<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class SupplierWisePurchaseReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:SWP Report',['only'=>'index']);
        $this->middleware('permission:SWP Report',['only'=>'getReport']);
    }
    public function index()
    {
        return view('backend.reports.supplier_wise_purchase.supplier_wise_purchase');
    }
    public function getReport(Request $request)
    {
        // return $request->all();
        $from_date=strtotime($request->from_date);
        $to_date=strtotime($request->to_date);
        $data=DB::select("
        SELECT units.name unit_name,concat(products.product_code,'-',products.name) name,
        ifnull((select sum(ifnull(purchases.deb_qantity,0)-ifnull(purchases.cred_qantity,0)) from purchases where product_id=products.id and supplier_id like :supplier),0) qantity,
        ifnull((select sum(purchases.price*(ifnull(purchases.deb_qantity,0)-ifnull(purchases.cred_qantity,0))) from purchases where product_id=products.id and supplier_id like :supplier2),0) price
        from products
        inner join units on units.id=products.unit_id
        where category_id like :category and products.id like :product
        group by products.id
        ",['category'=>'%'.$request->category.'%','product'=>'%'.$request->product.'%','supplier'=>'%'.$request->supplier,'supplier2'=>'%'.$request->supplier]);
        return response()->json($data);
    }
}
