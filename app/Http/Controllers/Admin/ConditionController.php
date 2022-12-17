<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Vinvoice;
use App\Models\Voucer;
use App\Models\AccountLedger;
use DataTables;
use Validator;
use URL;
class ConditionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Condition Sale List',['only'=>'index']);

    }

    public function index()
    {
    // return $get = Invoice::with('customer','pay')->where('sale_type',2)->get();

        if(request()->ajax()){
            $get = Invoice::with('customer','pay')->where('sale_type',2)->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('name',function($get){
                return (isset($get->customer->name) ? $get->customer->name :"not found");
              })
              ->addColumn('mobile',function($get){
                return (isset($get->customer->phone) ? $get->customer->phone :"not found");
              })
              ->addColumn('pay',function($get){
                $credit=$get->pay->sum('credit');
                $debit=$get->pay->sum('debit');
                return $debit-$credit;
              })
              ->addColumn('id',function($get){
                
                return '#'.str_pad($get->id,7,"0",STR_PAD_LEFT);
              })
            ->addColumn('action',function($get){
                $credit=$get->pay->sum('credit');
                $debit=$get->pay->sum('debit');

                $button ='<div class="d-flex justify-content-center">';
                $button.='<a   href="'.URL::to('admin/view-pages/sales-invoice/'.$get->id).'" class="btn btn-warning shadow btn-xs sharp me-1 editRow"><i class="fas fa-print"></i></a>
                          <a data-url="'.route('invoice.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
                $button.="<a class='btn btn-primary btn-xs ml-1 float-right' data-toggle='modal' data-target='#modal' data-whatever='@mdo' onclick='setInvoiceId(".$get->id.")'>pay</button>";
                $button.="<a class='btn btn-primary btn-xs ml-1 float-right' data-toggle='modal' data-target='#sleepModal' data-whatever='@mdo' onclick='setInvoiceId(".$get->id.")'>sleep</button>";
                $button.='</div>';
                return $button;
            })
          ->rawColumns(['action','name'])->make(true);
        }
        return view('backend.condition_list.condition');
    }

    public function store(Request $request)
    {
      //  return $request->all();

          $validator=Validator::make($request->all(),[
            'ammount'=>"required|max:200",
            'date'=>"required|max:200",
            'method'=>"required|max:1",
          ]);
        
        if($validator->passes()){
            $subledger=AccountLedger::where('name','Condition Customer')->first();
            $v_invoice=new Vinvoice;
            $v_invoice->date=strtotime($request->date);
            $v_invoice->total=$request->ammount;
            // action type 2 for supplier payment
            $v_invoice->action_type=3;
            $v_invoice->author_id = auth()->user()->id;
            $v_invoice->save();
            if($v_invoice){
                //condition credit
                $invoice=Invoice::find($request->invoice_id);
                $voucer=new Voucer;
                $voucer->date= strtotime($request->date);
                $voucer->transaction_name="Condition Sale Receipt";
                $voucer->v_inv_id= $v_invoice->id;
                $voucer->invoice_id=$request->invoice_id;
                $voucer->credit=$request->ammount;
                $voucer->ledger_id=$subledger->id;
                $voucer->subledger_id=$invoice->customer_id;
                $voucer->save();
                // Cash/Bank dabit
                if($request->method==0){
                    $ledger=AccountLedger::where('name','Cash')->first();
                }else{
                    $ledger=AccountLedger::where('name','Bank')->first();
                }
                $voucer=new Voucer;
                $voucer->date= strtotime($request->date);
                $voucer->transaction_name="Condition Sale Receipt";
                $voucer->v_inv_id= $v_invoice->id;
                $voucer->invoice_id= $request->invoice_id;
                $voucer->debit=$request->ammount;
                $voucer->ledger_id=$ledger->id;
                if($request->method!=0){
                  $voucer->subledger_id=$request->bank;
                }
                $voucer->save();
                if($voucer){
                    return response()->json(['message'=>'Condition Sale Receive Added']);
                }
            }
            
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }
}
