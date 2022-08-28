<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class PurchasePricingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('backend.reports.purchase_pricing.purchase_pricing');
    }
    public function getReport(Request $request)
    {
        // return $request->all();
        // $from_date=strtotime($request->from_date);
        $to_date=strtotime($request->to_date);
        
        $data=DB::select(
        "SELECT products.name,products.buy_price,
        ifnull((select price from purchases where product_id=products.id and dates<=:to_date order by id desc limit 1),0)  last_price
        from products
        -- where purchases.dates>=:from_date and purchases.dates<=:to_date
        group by products.id
        ",['to_date'=>$to_date]);
        return response()->json($data);
    }
}
