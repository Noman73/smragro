<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class CustomerWiseSalesReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:CWS Report',['only'=>'index']);
        $this->middleware('permission:CWS Report',['only'=>'getReport']);
    }
    public function index()
    {
        return view('backend.reports.customer_wise_sales.customer_wise_sales');
    }
    public function getReport(Request $request)
    {
        // return $request->all();
        $from_date=strtotime($request->from_date);
        $to_date=strtotime($request->to_date);
        $data=DB::select("
        SELECT concat(products.product_code,'-',products.name) name,
        ifnull((select sum(ifnull(sales.deb_qantity,0)-ifnull(sales.cred_qantity,0)) from sales where product_id=products.id and customer_id like :customer),0) qantity,
        ifnull((select sum(sales.price*(ifnull(sales.deb_qantity,0)-ifnull(sales.cred_qantity,0))) from sales where product_id=products.id and customer_id like :customer2),0) price
        from products
        where category_id like :category and products.id like :product
        group by products.id
        ",['category'=>'%'.$request->category.'%','product'=>'%'.$request->product.'%','customer'=>'%'.$request->customer,'customer2'=>'%'.$request->customer]);
        return response()->json($data);
    }
}
