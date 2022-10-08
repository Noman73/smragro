<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Price;
use Validator;
use DataTables;
use App\Rules\PriceCustomerRule;
use App\Rules\PriceProductRule;
use App\Rules\PricePriceRule;
use DB;
class MakePriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Sale Pricing View',['only'=>'index']);
        $this->middleware('permission:Sale Pricing Create',['only'=>'store']);
        $this->middleware('permission:Sale Pricing Edit',['only'=>'edit']);
        $this->middleware('permission:Sale Pricing Edit',['only'=>'update']);
        $this->middleware('permission:Sale Pricing Delete',['only'=>'destroy']);
    }
    public function index()
    {
        // return $get=Price::with('customer','product')->get();

        if(request()->ajax()){
            $get=Price::with('customer','product')->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
                $button ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('c-receive.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('c-receive.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          ->addColumn('date',function($get){
              return date('d-m-Y',$get->date);
          })
          ->addColumn('customer',function($get){
            return $get->customer->name;
          })
          ->addColumn('product',function($get){
            return $get->product->name;
          })
          ->rawColumns(['action'])->make(true);
        }
        return view("backend.make_price.make_price");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $data=$request->all();
        $data['product']= explode(',', $request->product);
        $data['remove']= explode(',', $request->remove);
        $data['price']= explode(',', $request->price);
        $validator=Validator::make($data,[
            'product'=>["required","array","max:200",new PriceProductRule],
            'price'=>["required","array","max:200",new PricePriceRule],
            'customer'=>["required","max:15"],
        ]);
        if($validator->passes()){
            // $delete=Price::where('customer_id',$request->customer)->delete();
            $i=0;
            foreach($data['remove'] as $val){
                $dlt=Price::where('customer_id',$request->customer)->Where('product_id',$val)->delete();
            }
            foreach($data['product'] as $value){
                $existing=Price::where('customer_id',$request->customer)->where('product_id',$value)->first();
                if(isset($existing->customer_id)){
                    $existing->price=$data['price'][$i];
                    $existing->save();
                    $i++;
                }else{
                    $store=new Price;
                    $store->product_id=$value;
                    $store->customer_id=$data['customer'];
                    $store->price=$data['price'][$i];
                    $store->save();
                    $i++;
                }
            }
            
            return response()->json(['message'=>'Price added Success']);
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function searchProduct(Request $request)
    {
        // return $request->all();
        $validator=Validator::make($request->all(),[
            'customer'=>["required","max:200"],
            'category'=>["nullable","max:200"],
        ]);

        if($validator->passes()){
            if($request->category==null){
                return DB::select("
                SELECT ifnull(prices.updated_at,'') as update_date,products.name,products.product_code,products.sale_price,ifnull(prices.price,0) set_price,ifnull(sales.price,0) last_price from products 
                left join prices on prices.product_id=products.id and prices.customer_id=:customer_id
                left join sales on sales.product_id=products.id and sales.customer_id=:customer_id2
            ",['customer_id'=>$request->customer,'customer_id2'=>$request->customer]);

            }else{
                return DB::select("
                SELECT products.name,products.product_code,products.sale_price,prices.price set_price,sales.price last_price from products 
                left join prices on prices.product_id=products.id and prices.customer_id=:customer_id
                left join sales on sales.product_id=products.id and sales.customer_id=:customer_id2
                where products.category_id=:category
            ",['customer_id'=>$request->customer,'customer_id2'=>$request->customer,'category'=>$request->category]);
            }
        }

        return response()->json(["error"=>$validator->getMessageBag()]);
    }

    public function priceList($customer_id){
        return Price::with('product')->where('customer_id',$customer_id)->get();
    }
}
