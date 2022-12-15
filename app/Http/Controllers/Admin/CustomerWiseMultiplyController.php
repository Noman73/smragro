<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerMultiply;
use Validator;
use DataTables;
class CustomerWiseMultiplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $get=CustomerMultiply::with('customer');
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('customer-multiply.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('customer-multiply.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          ->addColumn('name',function($get){
          return $get->customer->name;
        })
          ->rawColumns(['action'])->make(true);
        }
        return view('backend.customer_multiplies.customer_multiplies');
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
            'customer'=>"required|max:15|min:1|unique:customer_multiplies,customer_id",
            'multiply'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
            $multiply=new CustomerMultiply;
            $multiply->customer_id=$request->customer;
            $multiply->multiply=$request->multiply;
            $multiply->author_id=auth()->user()->id;
            $multiply->save();
            if ($multiply) {
                return response()->json(['message'=>'Multiply Added Success']);
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
        return response()->json(CustomerMultiply::with('customer')->find($id));
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
            'customer'=>"required|max:15|min:1|unique:customer_multiplies,customer_id,".$id,
            'multiply'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
            $multiply=CustomerMultiply::find($id);
            $multiply->customer_id=$request->customer;
            $multiply->multiply=$request->multiply;
            $multiply->author_id=auth()->user()->id;
            $multiply->save();
            if ($multiply) {
                return response()->json(['message'=>'Multiply Added Success']);
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

    public function getMultiply($customer_id)
    {
       return CustomerMultiply::where('customer_id',$customer_id)->first(); 
    }
}
