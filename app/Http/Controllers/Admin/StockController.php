<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use DB;
class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if(request()->ajax()){
            $get = $this->stockWithWarehouse();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('qantity',function($get){
              return $get->qantity.' '.$get->unit_name;
          })
          ->addColumn('name',function($get){
            return $get->product_code.'-'.$get->name;
        })
        ->setRowClass(function ($get) {
            return $get->qantity<=$get->reorder_level ? 'bg-danger' : '';
        })
          ->rawColumns(['action'])->make(true);
        }
        return view('backend.stock.stock');
    }

    public function stockWithoutWarehouse()
    {
        return $get = DB::select("
            SELECT units.name unit_name,products.reorder_level,products.product_code,products.name,ifnull(sum(purchases.deb_qantity-purchases.cred_qantity),0)-ifnull(sales1.deb_qantity,0) qantity from
            products
            left join purchases on purchases.product_id=products.id and (purchases.action_id=0 or purchases.action_id=2 or purchases.action_id=3)
            left join (
            select product_id,ifnull(sum(deb_qantity)-sum(cred_qantity),0) deb_qantity from sales where action_id=0 or action_id=1  group by product_id
            ) as sales1 on sales1.product_id=products.id
            inner join units on units.id=products.unit_id
            where products.combo<>1
            group by products.id,purchases.product_id,sales1.product_id order by products.id
                ");
    }
    public function stockWithWarehouse()
    {
        return $get = DB::select("
        SELECT units.name unit_name,warehouses.name store,products.name,products.product_code,products.reorder_level ,ifnull(sum(purchases.deb_qantity-purchases.cred_qantity),0)-ifnull(sales1.deb_qantity,0) qantity from
        products
        left join purchases on purchases.product_id=products.id and (purchases.action_id=0 or purchases.action_id=2 or purchases.action_id=3)
        left join (
        select product_id,store_id,ifnull(sum(deb_qantity)-sum(cred_qantity),0) deb_qantity from sales where (action_id=0 or action_id=1) group by product_id,store_id
        ) as sales1 on (sales1.product_id=products.id and purchases.store_id=sales1.store_id)
        left join warehouses on (sales1.store_id=warehouses.id or purchases.store_id=warehouses.id)
        inner join units on units.id=products.unit_id
            where products.combo<>1
        group by products.id,sales1.store_id,purchases.store_id,purchases.product_id,sales1.product_id order by products.name
                        ");
    }
}
