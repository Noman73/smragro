<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class SalePricingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Sale Pricing Report',['only'=>'index']);
        $this->middleware('permission:Sale Pricing Report',['only'=>'getReport']);
    }
    public function index()
    {
        return view('backend.reports.sale_pricing.sale_pricing');
    }
    public function getReport(Request $request)
    {
        // return $request->all();
        // $from_date=strtotime($request->from_date);
        $to_date=strtotime($request->to_date);
        $data=DB::select(
        "SELECT products.name,products.sale_price,
        ifnull((select price from sales where product_id=products.id and dates<=:to_date and customer_id=:customer order by id desc limit 1),0)  last_price
        from products
        -- where purchases.dates>=:from_date and purchases.dates<=:to_date
        group by products.id
        ",['to_date'=>$to_date,'customer'=>$request->customer]);
        return response()->json($data);
    }
}
