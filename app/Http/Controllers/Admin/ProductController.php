<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DataTables;
use App\Models\Product;
use Auth;
use DB;
use App\Models\Price;
use App\Models\Sale;
use App\Models\Models;
use App\Models\Brand;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Product View',['only'=>'index']);
        $this->middleware('permission:Product Create',['only'=>'store']);
        $this->middleware('permission:Product Edit',['only'=>'edit']);
        $this->middleware('permission:Product Edit',['only'=>'update']);
        $this->middleware('permission:Product Delete',['only'=>'destroy']);
    }
    public function index()
    {
        if(request()->ajax()){
            $get=Product::with('category')->orderBy('category_id','asc')->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('product.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('product.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              
              $button.='</div>';
            return $button;
          })
          ->addColumn('image',function($get){
            if($get->image!=null){
                return $img="<img style='width:80px;height:60px;' src=".asset('storage/product/').'/'.$get->image."/>";
            }
            return $img="<img style='width:80px;height:60px;' src=".asset('storage/product/no-image.png')."/>";
        })
        ->addColumn('category',function($get){
           return $get->category->name;
         })
         ->addColumn('name',function($get){
            return $get->product_code.'-'.$get->name;
          })
         ->addColumn('status',function($get){
            return ($get->status ? '<span class="bg-success p-1 rounded">Active</span>':'<span class="bg-danger p-1 rounded">Deactive</span>');
          })
          ->rawColumns(['action','image','status'])->make(true);
        }
        return view('backend.product.product');
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
        $validator=Validator::make($request->all(),[
            'category'=>"required|max:20|min:1",
            'brand'=>"required|max:20|min:1",
            'part_id'=>"required|max:20|min:1",
            'name'=>"required|max:200|min:1",
            'product_code'=>"nullable|max:200|min:1",
            'model_no'=>"nullable|max:200|min:1",
            'warranty'=>"nullable|max:200|min:1",
            'unit_type'=>"nullable|max:200|min:1",
            'sale_price'=>"nullable|max:200|min:1",
            'buy_price'=>"nullable|max:200|min:1",
            'reorder_level'=>"required|max:200|min:1",
            'sale'=>"nullable|max:200|min:1",
            'purchase'=>"nullable|max:200|min:1",
            'production'=>"nullable|max:200|min:1",
            'combo'=>"nullable|max:200|min:1",
            'status'=>"required|max:1|min:1",
            'image'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if($validator->passes()){
            $existance=Product::where('category_id',$request->category)->count();
            $product=new Product;
            $product->category_id=$request->category;
            $product->brand_id=$request->brand;
            $product->part_id=$request->part_id;
            $product->name=$request->name;
            $product->product_code=str_pad($request->category,4,'0').($existance!=0 ? (intval($existance)+1) : 1);
            $product->model_no=$request->model_no;
            $product->warranty=$request->warranty;
            $product->unit_id=$request->unit_type;
            $product->sale=$request->sale;
            $product->purchase=$request->purchase;
            $product->production=$request->production;
            $product->combo=$request->combo;
            $product->combobox=$request->products;
            $product->comboqty=$request->qantity;
            $product->sale_price=$request->sale_price;
            $product->buy_price=$request->buy_price;
            $product->reorder_level=$request->reorder_level;
            $product->status=$request->status;
            if ($request->hasFile('image')) {
                $ext = $request->image->getClientOriginalExtension();
                $name = Auth::user()->id  . time() . '.' . $ext;
                $request->image->storeAs('public/product', $name);
                $product->image = $name;
            }
            $product->save();
            if ($product) {
                return response()->json(['message'=>'Product Added Success']);
            }
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
        return response()->json(Product::with('category','unit')->find($id));
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
        // return $request->all();
        $validator=Validator::make($request->all(),[
            'category'=>"required|max:200|min:1",
            'name'=>"required|max:200|min:1",
            'product_code'=>"required|max:200|min:1",
            'model_no'=>"nullable|max:200|min:1",
            'warranty'=>"nullable|max:200|min:1",
            'unit_type'=>"required|max:200|min:1",
            'sale_price'=>"nullable|max:200|min:1",
            'buy_price'=>"nullable|max:200|min:1",
            'reorder_level'=>"required|max:200|min:1",
            'sale'=>"nullable|max:200|min:1",
            'purchase'=>"nullable|max:200|min:1",
            'production'=>"nullable|max:200|min:1",
            'combo'=>"nullable|max:200|min:1",
            'status'=>"nullable|max:1|min:1",
            'image'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if($validator->passes()){
            $existance=Product::where('category_id',$request->category)->count();
            $product=Product::find($id);
            $product->category_id=$request->category;
            $product->name=$request->name;
            $product->product_code=str_pad($request->category,4,'0').($existance!=0 ? (intval($existance)+1) : 1);
            $product->model_no=$request->model_no;
            $product->warranty=$request->warranty;
            $product->unit_id=$request->unit_type;
            $product->combobox=$request->products;
            $product->comboqty=$request->qantity;
            $product->sale_price=$request->sale_price;
            $product->buy_price=$request->buy_price;
            $product->reorder_level=$request->reorder_level;
            $product->sale=$request->sale;
            $product->purchase=$request->purchase;
            $product->production=$request->production;
            $product->combo=$request->combo;
            $product->status=$request->status;
            if ($request->hasFile('image')) {
                if($product->image!=null){
                    unlink(storage_path('app/public/product/'.$product->image));
                }
                $ext = $request->image->getClientOriginalExtension();
                $name = Auth::user()->id  . time() . '.' . $ext;
                $request->image->storeAs('public/product', $name);
                $product->image = $name;
            }
            $product->save();
            if ($product) {
                return response()->json(['message'=>'Product Updated Success']);
            }
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $img=Product::find($id);
        $delete=Product::where('id',$id)->delete();
        if ($delete) {
            unlink(storage_path('app/public/product/'.$img->image));
            return response()->json(['message'=>'Product Deleted Success']);
        }else{
            return response()->json(['warning'=>'Something Wrong']);
        }
    }
    public function getProduct(Request $request)
    {
        $products= Product::where('name','like','%'.$request->searchTerm.'%')->orWhere('part_id','like','%'.$request->searchTerm.'%')->where('status',1)->take(15)->get();
        foreach ($products as $value){
             $set_data[]=['id'=>$value->id,'text'=>$value->part_id.'-'.$value->name];
        }
        return $set_data;
    }
    public function getProductWithoutCombo(Request $request)
    {
        $searchTerm=$request->searchTerm;
        $products= Product::where('purchase',1)
        ->where(function($query) use ($searchTerm){
            $query->where('name','like','%'.$searchTerm.'%')
                     ->orWhere('product_code','like','%'.$searchTerm.'%');
        })
        ->take(15)->get();
        // $products= Product::where('name','like','%'.$request->searchTerm.'%')->orWhere('product_code','like','%'.$request->searchTerm.'%')->where('purchase',1)->where('combo',0)->take(15)->get();
        foreach ($products as $value){
             $set_data[]=['id'=>$value->id,'text'=>$value->product_code.'-'.$value->name];
        }
        return $set_data;
    }
    public function productCountByCat($category_id)
    {
        return Product::where('category_id',$category_id)->count();
    }

    public function getQantity($product_id)
    {
        // return $product_id;
       return DB::select('
       SELECT ifnull((SELECT sum(deb_qantity)-sum(cred_qantity) from purchases where product_id=:product_id ),0)-ifnull((SELECT sum(deb_qantity)-sum(cred_qantity) from sales where product_id=:product_id2),0) as total
       ',['product_id'=>$product_id,'product_id2'=>$product_id])[0];
    }

    public function getPrice(Request $request){
        $product_id=$request->product;
        $customer_id=$request->customer;
        if($customer_id=='null'){
            $price=DB::table('sales')->select('price')->where('product_id',$product_id)->orderBy('id','desc')->first();
            if($price!=null){
                return $price->price;
            }else{
                return Product::select('sale_price')->where('id',$product_id)->first()->sale_price;
            }
        }else{
            $price= Price::select('price')->where('product_id',$product_id)->where('customer_id',$customer_id)->first();
        }
    }

    public function productPrice(Request $request)
    {
        // return $request->all();
        if($request->customer!=null){
            $price_defined=Price::where('customer_id',$request->customer)->where('product_id',$request->product)->first();
            if($price_defined==null){
               $price_defined=Sale::where('customer_id',$request->customer)->where('product_id',$request->product)->orWhere('product_id',$request->product)->orderBy('id','desc')->first();
            }
            if($price_defined==null){
               $price_defined=Product::where('id',$request->product)->first();            
            }
            info('ok');
        }else{
            $price_defined=Sale::where('product_id',$request->product)->orderBy('id','desc')->first();
            if($price_defined==null){
                $price_defined=Product::where('id',$request->product)->first();            
            }

        }
        if(isset($price_defined->price)){
            $price= $price_defined->price;
        }else{
            $price= $price_defined->sale_price;
        }
        return response()->json($price);
        
    }

    public function getAllProduct(Request $request)
    {
        $category=$request->category==null ? '': $request->category;
        $search=$request->search==null ? '': $request->search;
        $brand=$request->brand;
        // return $request->all();
        return $get = DB::select("
            SELECT products.id,units.name unit_name,products.reorder_level,products.image,products.product_code,products.name,products.sale_price,if(products.combo=1,'N/A',ifnull(sum(purchases.deb_qantity-purchases.cred_qantity),0)-ifnull(sales1.deb_qantity,0)) qantity from
            products
            left join purchases on purchases.product_id=products.id and (purchases.action_id=0 or purchases.action_id=2 or purchases.action_id=3)
            left join (
            select product_id,ifnull(sum(deb_qantity)-sum(cred_qantity),0) deb_qantity from sales where action_id=0 or action_id=1  group by product_id
            ) as sales1 on sales1.product_id=products.id
            inner join units on units.id=products.unit_id
            where products.category_id like :category and products.name like :search
            group by products.id,purchases.product_id,sales1.product_id order by products.id limit 9
            ",['category'=>'%'.$category.'%','search'=>'%'.$search.'%']);
    }
    public function getProductByCode($code){
        // return $code;
        return $get = DB::select("
            SELECT products.id,units.name unit_name,products.reorder_level,products.image,products.product_code,products.name,products.sale_price,if(products.combo=1,'N/A',ifnull(sum(purchases.deb_qantity-purchases.cred_qantity),0)-ifnull(sales1.deb_qantity,0)) qantity from
            products
            left join purchases on purchases.product_id=products.id and (purchases.action_id=0 or purchases.action_id=2 or purchases.action_id=3)
            left join (
            select product_id,ifnull(sum(deb_qantity)-sum(cred_qantity),0) deb_qantity from sales where action_id=0 or action_id=1  group by product_id
            ) as sales1 on sales1.product_id=products.id
            inner join units on units.id=products.unit_id
            where products.product_code=:code
            group by products.id,purchases.product_id,sales1.product_id order by products.id limit 9
            ",['code'=>$code]);
    }
    public function getProductSalePrice($product_id)
    {
        return Product::find($product_id)->sale_price;
    }
    public function getModel(Request $request)
    {
        // return $request->all();
        $brand_id=$request->brand_id;
        if($request->brand_id==null){
            $brand_id='';
            // $search= Models::select('id','name')->where('name','like','%'.$request->searchTerm.'%')->take(100)->get();
            $search=DB::select("
                SELECT models.id,models.name from brand_has_models
                inner join models on models.id=brand_has_models.model_id
                where models.name like :query group by models.id
            ",['query'=>'%'.$request->searchTerm.'%']);
        }else{
            // $search= Models::select('id','name')->where('name','like','%'.$request->searchTerm.'%')->where('brand_id',$brand_id)->take(100)->get();
            $search=DB::select("
                SELECT models.id,models.name from brand_has_models
                inner join models on brand_has_models.model_id=models.id
                where models.name like :query and brand_has_models.brand_id=:brand_id  group by models.id
            ",['query'=>'%'.$request->searchTerm.'%','brand_id'=>$brand_id]);
        }
        foreach ($search as $value){
            $set_data[]=['id'=>$value->id,'text'=>$value->name];
       }
       return $set_data;
    }
    public function getProductByData(Request $request)
    {
        // return $request->all();
        $brand_id=($request->brand_id==null? '':$request->brand_id);
        $model=($request->model_id==null? '':$request->model_id);
        $part_id=($request->part_id==null? '':$request->part_id);
        
        if($part_id=='' and $model=='' and $brand_id==''){
            $search=Product::where('name','like','%'.$request->searchTerm.'%')->orWhere('part_id',$part_id)->groupBy('name')->take(30)->get();
        }elseif($part_id=='' ){
             $search=Product::where('name','like','%'.$request->searchTerm.'%')->where('brand_id',$brand_id)->orWhere('model_id',$model)->groupBy('name')->take(30)->get();
        }else{
            $search=Product::where('name','like','%'.$request->searchTerm.'%')->where('part_id',$part_id)->groupBy('name')->take(30)->get();
        }
        foreach ($search as $value){
            $set_data[]=['id'=>$value->id,'text'=>$value->name];
        }
        return $set_data;
    }

    public function getPartId(Request $request)
    {
        $search=Product::where('part_id','like','%'.$request->searchTerm.'%')->take(30)->get();
        foreach ($search as $value){
            $set_data[]=['id'=>$value->part_id,'text'=>$value->part_id];
        }
        return $set_data;
    }
    public function getAllData($id){
      return Product::with('brand','model')->where('id',$id)->first();  
    }
    public function getAllPartIdData($id)
    {
        return Product::with('brand','model')->where('part_id',$id)->first();
    }
    public function productDetails(Request $request){
        // return $request->text;
        $model=$request->model;
        $brand=$request->brand;
        $post=Product::with('model','brand')->where('name',$request->text)->get();
        if($model!=null and $brand!=null){
            $post=Product::with('model','brand')->where('model_id',$model)->where('brand_id',$brand)->where('name',$request->text)->get();
        }
        if($model==null and $brand!=null){
            $post=Product::with('model','brand')->where('brand_id',$brand)->where('name',$request->text)->get();
        }
        if($model!=null and $brand==null){
            $post=Product::with('model','brand')->where('model_id',$model)->where('name',$request->text)->get();
        }
        return $post;
    }
}
