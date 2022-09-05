<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\CreditSetup;
use App\Rules\PricePriceRule;
use DataTables;
use URL;
class CreditSetupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        //   return  $get=CreditSetup::with('customer')->get();
        if(request()->ajax()){
            $get=CreditSetup::with('customer')->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a href="javascript:void(0)"  data-url="'.route('credit-setup.edit',$get->id).'" class="btn btn-warning shadow btn-xs sharp me-1 editRow"><i class="fas fa-pen"></i></a>
              <a data-url="'.route('credit-setup.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          ->addColumn('customer',function($get){
              return ($get->customer->code.'-'.$get->customer->name);
          })
          ->rawColumns(['action'])->make(true);
        }
        return view('backend.credit_setup.credit_setup');
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
            'customer'=>["required","max:200","min:1"],
            'amount'=>"required|max:20|min:1",
        ]);
        if($validator->passes()){
            $creditSetup=new CreditSetup;
            $creditSetup->customer_id=$request->customer;
            $creditSetup->amount=$request->amount;
            $creditSetup->author_id=auth()->user()->id;
            $creditSetup->save();
            if ($creditSetup) {
                return response()->json(['message'=>'Credit Added Success']);
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
        return response()->json(CreditSetup::with('customer')->where('id',$id)->first());
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
            'customer'=>["required","max:200","min:1","unique:credit_setups,customer_id,".$request->customer],
            'amount'=>"required|max:20|min:1",
        ]);
        if($validator->passes()){
            $creditSetup=CreditSetup::find($id);
            $creditSetup->customer_id=$request->customer;
            $creditSetup->amount=$request->amount;
            $creditSetup->author_id=auth()->user()->id;
            $creditSetup->save();
            if ($creditSetup) {
                return response()->json(['message'=>'Credit Updated Success']);
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
}
