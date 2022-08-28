<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
class ItemListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('backend.reports.item_list.item_list');
    }
    public function getReport(Request $request)
    {
        // return $request->all();
        // $from_date=strtotime($request->from_date);
        $to_date=strtotime($request->to_date);
        $data=Product::with('category')->orderBy('category_id','asc')->get();
        return response()->json($data);
    }
}
