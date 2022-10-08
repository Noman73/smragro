<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingCompany;
use Validator;
use DataTables;
class ShippingCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Shipping Company View',['only'=>'index']);
        $this->middleware('permission:Shipping Company Create',['only'=>'store']);
        $this->middleware('permission:Shipping Company Edit',['only'=>'edit']);
        $this->middleware('permission:Shipping Company Edit',['only'=>'update']);
        $this->middleware('permission:Shipping Company Delete',['only'=>'destroy']);
    }
    public function index()
    {
        if(request()->ajax()){
            $get=ShippingCompany::query();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('shipping-company.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('shipping-company.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              
              $button.='</div>';
            return $button;
          })
          ->rawColumns(['action'])->make(true);
        }
        return view('backend.shipping_company.shipping_company');
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
        $validator=Validator::make($request->all(),[
            'name'=>"required|max:200|min:1",
            'adress'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
            $shipping_company=new ShippingCompany;
            $shipping_company->name=$request->name;
            $shipping_company->adress=$request->adress;
            $shipping_company->author_id=auth()->user()->id;
            $shipping_company->save();
            if ($shipping_company) {
                return response()->json(['message'=>'Shipping Company Added Success']);
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
        return response()->json(ShippingCompany::find($id));
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
        $validator=Validator::make($request->all(),[
            'name'=>"required|max:200|min:1",
            'adress'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
            $shipping_company=ShippingCompany::find($id);
            $shipping_company->name=$request->name;
            $shipping_company->adress=$request->adress;
            $shipping_company->author_id=auth()->user()->id;
            $shipping_company->save();
            if ($shipping_company) {
                return response()->json(['message'=>'Shipping Company Updated Success']);
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
        $delete=ShippingCompany::where('id',$id)->delete();
        if ($delete) {
            return response()->json(['message'=>'Shipping Company Deleted']);
        }else{
            return response()->json(['warning'=>'Something Wrong']);
        }
    }

    public function getShippingCompany(Request $request)
    {
        $donors= ShippingCompany::where('name','like','%'.$request->searchTerm.'%')->take(15)->get();
        foreach ($donors as $value){
             $set_data[]=['id'=>$value->id,'text'=>$value->name];
         }
         return $set_data;
    }
}
