<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucer;
class VoucerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'ledger'=>"required|max:200|min:1",
            'sub_ledger'=>"required|max:200|min:1",
            'ammount'=>"required|max:200|min:1",
            'payment_method'=>"required|max:200|min:1",
            'bank'=>"nullable|max:200|min:1",
            'cheque_no'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
            $voucer=new Voucer;
            $voucer->ledger_id=$request->ledger;
            $voucer->sub_ledger=$request->sub_ledger;
            $voucer->account_id=$request->account_id;
            $voucer->cheque_no=$request->cheque_no;
            $voucer->cheque=$request->cheque;
            $voucer->author_id=auth()->user()->id;
            $voucer->save();
            if ($voucer) {
                return response()->json(['message'=>'Account Voucer Added Success']);
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
}
