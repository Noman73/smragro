<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Supplier;
use App\Models\AccountLedger;
use Auth;
use DataTables;
use DB;
class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Supplier View',['only'=>'index']);
        $this->middleware('permission:Supplier Create',['only'=>'store']);
        $this->middleware('permission:Supplier Edit',['only'=>'edit']);
        $this->middleware('permission:Supplier Edit',['only'=>'update']);
        $this->middleware('permission:Supplier Delete',['only'=>'destroy']);
    }
    public function index()
    {
        if(request()->ajax()){
            $get=Supplier::query();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('supplier.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('supplier.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          
          ->rawColumns(['action'])->make(true);
        }
        return view('backend.supplier.supplier');
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
            'name'=>"required|max:200|min:1",
            'email'=>"nullable|email|max:200|min:1",
            'phone'=>"nullable|max:200|min:1",
            'adress'=>"nullable|max:200|min:1",
            'supplier_type'=>"required|max:200|min:1",
            'image'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048|',
        ]);
        if($validator->passes()){
            $ledger_code=AccountLedger::where('name','Supplier')->first();
            $countSupplier=Supplier::count();
            $product=new Supplier;
            $product->name=$request->name;
            $product->email=$request->email;
            $product->phone=$request->phone;
            $product->adress=$request->adress;
            $product->code=$ledger_code->code.'-'.$countSupplier+1;
            $product->supplier_type=$request->supplier_type;
            $product->author_id=auth()->user()->id;
            if ($request->hasFile('image')) {
                $ext = $request->image->getClientOriginalExtension();
                $name = auth()->user()->id  . time() . '.' . $ext;
                $request->image->storeAs('public/product', $name);
                $product->image = $name;
            }
            $product->save();
            if ($product) {
                return response()->json(['message'=>'Supplier Added Success']);
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
        return response()->json(Supplier::find($id));
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
            'name'=>"required|max:200|min:1",
            'email'=>"nullable|email|max:200|min:1",
            'phone'=>"nullable|max:200|min:1",
            'adress'=>"nullable|max:200|min:1",
            'image'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if($validator->passes()){

            $product=Supplier::find($id);
            $product->name=$request->name;
            $product->email=$request->email;
            $product->phone=$request->phone;
            $product->adress=$request->adress;
            $product->supplier_type=$request->supplier_type;
            $product->author_id=auth()->user()->id;
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
                return response()->json(['message'=>'Supplier Added Success']);
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
        //
    }
    public function getSupplier(Request $request){
        $supplier= Supplier::where('name','like','%'.$request->searchTerm.'%')->orWhere('code','like','%'.$request->searchTerm.'%')->take(15)->get();
        foreach ($supplier as $value){
             $set_data[]=['id'=>$value->id,'text'=>$value->name.'('.$value->phone.')'];
        }
        return $set_data;
     }

     public function getBalance($id)
     {
         return $total=DB::select("select sum(voucers.credit-voucers.debit) total from voucers left join account_ledgers on account_ledgers.id=voucers.ledger_id where account_ledgers.name='Supplier' and (subledger_id=:id or person_id=:person_id)",['id'=>$id,'person_id'=>$id]);
     }
}
