<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class InventoryReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('backend.reports.inventory_report.inventory_report');
    }
    public function getReport(Request $request)
    {
        // return $request->all();
        $from_date=strtotime($request->from_date);
        $to_date=strtotime($request->to_date);
        $data=DB::select("
        SELECT products.name,
        ifnull((select sum(ifnull(purchases.deb_qantity,0)-ifnull(purchases.cred_qantity,0)) from purchases where product_id=products.id ),0) stock_in,
        ifnull((select sum(ifnull(sales.deb_qantity,0)-ifnull(sales.cred_qantity,0)) from sales where product_id=products.id),0) stock_out,
        ifnull((select sum(purchases.price*(ifnull(purchases.deb_qantity,0)-ifnull(purchases.cred_qantity,0))) from purchases where product_id=products.id),0) price
        from products
        where category_id like :category and products.id like :product
        group by products.id
        ",['category'=>'%'.$request->category,'product'=>'%'.$request->product]);
        return response()->json($data);
    }
}
