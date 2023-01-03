<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Models\Vinvoice;
use App\Models\Voucer;
use Validator;
use App\Rules\FundTransferAmmountRule;
use App\Rules\FundTransferToMethodRule;
use App\Rules\FundTransferToBankRule;
use DataTables;
use URL;
use App\Http\FtInvoice;
class FundTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Bank Transfer View',['only'=>'index']);
        $this->middleware('permission:Bank Transfer Create',['only'=>'store']);
        $this->middleware('permission:Bank Transfer Edit',['only'=>'edit']);
        $this->middleware('permission:Bank Transfer Edit',['only'=>'update']);
        $this->middleware('permission:Bank Transfer Delete',['only'=>'destroy']);
    }
    public function index()
    {
        if(request()->ajax()){
            $get=Vinvoice::where('action_type',4)->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url=""  href="'.URL::to('admin/view-pages/fund-transfer-view/'.$get->id).'" class="btn btn-warning shadow btn-xs sharp me-1 editRow"><i class="fas fa-print"></i></a>
                <a data-url="'.route('fund_transfer.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp ml-1 editRow"><i class="fas fa-pen"></i></a>
              <a data-url="'.route('fund_transfer.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          ->addColumn('date',function($get){
              return date('d-m-Y',$get->date);
          })
          ->addColumn('trx_id',function($get){
            return 'F-'.date('dm',$get->date).substr(date('Y',$get->date),-2).$get->id;
        })
          ->rawColumns(['action'])->make(true);
        }
        return view('backend.fund_transfer.fund_transfer');
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
            'ammount'=>["required","max:200",new FundTransferAmmountRule($request->from_method,$request->from_bank)],
            'date'=>"required|max:200",
            'from_method'=>"required|max:1",
            'to_method'=>["nullable","max:1",new FundTransferToMethodRule($request->from_method,$request->from_bank,$request->to_bank)],
            'from_bank'=>"nullable|max:20",
            'to_bank'=>["nullable","max:20",new FundTransferToBankRule($request->from_bank)],
            'note'=>"nullable|max:200",
        ]);

        if($validator->passes()){
            $customer_ledger=AccountLedger::where('name','Customer')->first();
            $v_invoice=new Vinvoice;
            $v_invoice->date=strtotime($request->date);
            $v_invoice->total=$request->ammount;
            // action type 4 for fund transfer
            $v_invoice->action_type=4;
            $v_invoice->author_id = auth()->user()->id;
            $v_invoice->save();
            if($v_invoice){
                if($request->to_method==0){
                    $to_ledger=AccountLedger::where('name','Cash')->first();
                }else{
                    $to_ledger=AccountLedger::where('name','Bank')->first();
                }
                if($request->from_method==0){
                    $from_ledger=AccountLedger::where('name','Cash')->first();
                }else{
                    $from_ledger=AccountLedger::where('name','Bank')->first();
                }

                $voucer=new Voucer;
                $voucer->date= strtotime($request->date);
                $voucer->transaction_name="Fund Transfer";
                $voucer->v_inv_id= $v_invoice->id;
                $voucer->debit=$request->ammount;
                $voucer->ledger_id=$to_ledger->id;
                if($request->to_bank!='null'){
                    $voucer->subledger_id=$request->to_bank;
                }
                $voucer->author_id = auth()->user()->id;
                $voucer->comment=$request->note;
                $voucer->save();
                // from_method credit
                $voucer=new Voucer;
                $voucer->date= strtotime($request->date);
                $voucer->transaction_name="Fund Transfer";
                $voucer->v_inv_id= $v_invoice->id;
                $voucer->credit=$request->ammount;
                $voucer->ledger_id=$from_ledger->id;
                if($request->from_bank!='null'){
                    $voucer->subledger_id=$request->from_bank;
                }
                $voucer->author_id = auth()->user()->id;
                $voucer->comment=$request->note;
                $voucer->save();
                if($voucer){
                    return response()->json(['message'=>'Fund Transfer Added','id'=>$v_invoice->id]);
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
