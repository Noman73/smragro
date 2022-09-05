<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Invoice;
use App\Models\AccountLedger;
use App\Models\VInvoice;
use App\Models\Voucer;
use App\Rules\ZeroValidationRule;
class RegularReceiveController extends Controller
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
            'ammount'=>["required",new ZeroValidationRule],
            'date'=>"required|max:200",
            'method'=>"required|max:1",
            // 'note'=>"nullable|max:500",
        ]);

        if($validator->passes()){
            $customer_id=Invoice::where('id',$request->invoice_id)->first()->customer_id;
            $customer_ledger=AccountLedger::where('name','Customer')->first();
            $v_invoice=new Vinvoice;
            $v_invoice->date=strtotime($request->date);
            $v_invoice->total=$request->ammount;
            $v_invoice->note=$request->note;
            $v_invoice->customer_id=$request->customer;
            // action type 2 for supplier payment
            $v_invoice->action_type=3;
            $v_invoice->author_id = auth()->user()->id;
            $v_invoice->save();
            if($v_invoice){
                // customer 
                $voucer=new Voucer;
                $voucer->date= strtotime($request->date);
                $voucer->transaction_name="Customer Receive";
                $voucer->v_inv_id= $v_invoice->id;
                $voucer->credit=$request->ammount;
                $voucer->ledger_id=$customer_ledger->id;
                $voucer->subledger_id=$customer_id;
                $voucer->author_id= auth()->user()->id;
                $voucer->save();
                // Cash/Bank dabit
                if($request->method==0){
                    $ledger=AccountLedger::where('name','Cash')->first();
                }else{
                    $ledger=AccountLedger::where('name','Bank')->first();
                }
                $voucer=new Voucer;
                $voucer->date= strtotime($request->date);
                $voucer->transaction_name="Customer Receive";
                $voucer->v_inv_id= $v_invoice->id;
                $voucer->debit=$request->ammount;
                $voucer->ledger_id=$ledger->id;
                if($request->bank=='null'){
                    $request->bank=null;
                }
                if($request->method==1){
                    $voucer->cheque_no=$request->cheque_no;
                    $voucer->cheque_issue_date=strtotime($request->issue_date);
                    // $voucer->cheque_photo=$data['cheque_no'];
                }
                $voucer->subledger_id=$request->bank;
                $voucer->author_id= auth()->user()->id;
                $voucer->save();
                if($voucer){
                    return response()->json(['message'=>'Customer Receive Added']);
                }
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
