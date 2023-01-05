<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucer;
use App\Models\Vinvoice;
use App\Models\AccountLedger;
use Validator;
use DataTables;
use URL;
use App\Rules\ZeroValidationRule;
use DB;
class SPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Supplier Payment View',['only'=>'index']);
        $this->middleware('permission:Supplier Payment Create',['only'=>'store']);
        $this->middleware('permission:Supplier Payment Edit',['only'=>'edit']);
        $this->middleware('permission:Supplier Payment Edit',['only'=>'update']);
        $this->middleware('permission:Supplier Payment Delete',['only'=>'destroy']);
    }
    public function index()
    {
        if(request()->ajax()){
            $get=Vinvoice::where('action_type',2)->orderBy('date','desc');
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
              $button.='<a data-url="'.route('s-payment.edit',$get->id).'"  href="javascript:void()" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>';
                $button.='<a data-url=""  href="'.URL::to('admin/view-pages/payment-view/'.$get->id).'" class="btn btn-warning shadow btn-xs sharp me-1 ml-1 "><i class="fas fa-print"></i></a>
              <a data-url="'.route('s-payment.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          ->addColumn('date',function($get){
              return date('d-m-Y',$get->date);
          })
          ->addColumn('trx_id',function($get){
            return 'P-'.date('dm',$get->date).substr(date('Y',$get->date),-2).$get->id;
        })
          ->rawColumns(['action'])->make(true);
        }
        return view('backend.s_payment.s_payment');
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
        if($data['supplier']=="null"){
            $data['supplier']=null;
        }
        if($data['bank']=="null"){
            $data['bank']=null;
        }
        if($data['method']==0){
            $bank_cond='nullable';
        }else{
            $bank_cond="required";
        }
        $validator=Validator::make($data,[
            'supplier'=>"required|max:200",
            'ammount'=>["required",new ZeroValidationRule],
            'date'=>"required|max:200",
            'method'=>"required|max:1",
            'note'=>"nullable|max:500",
            'bank'=>$bank_cond."|max:500",
        ]);
        // if($request->)
        if($validator->passes()){
            $subledger=AccountLedger::where('name','Supplier')->first();
            $v_invoice=new Vinvoice;
            $v_invoice->date=strtotime($request->date);
            $v_invoice->total=$request->ammount;
            $v_invoice->note=$request->note;
            $v_invoice->supplier_id=$request->supplier;
            // action type 2 for supplier payment
            $v_invoice->action_type=2;
            $v_invoice->author_id = auth()->user()->id;
            $v_invoice->save();
            if($v_invoice){
                // supplier dabit
                $voucer=new Voucer;
                $voucer->date= strtotime($request->date);
                $voucer->transaction_name="Supplier Payment";
                $voucer->v_inv_id= $v_invoice->id;
                $voucer->debit=$request->ammount;
                $voucer->credit=0;
                $voucer->ledger_id=$subledger->id;
                $voucer->subledger_id=$request->supplier;
                $voucer->author_id = auth()->user()->id;
                $voucer->save();
                // Cash/Bank dabit
                if($request->method==0){
                    $ledger=AccountLedger::where('name','Cash')->first();
                }else{
                    $ledger=AccountLedger::where('name','Bank')->first();
                }
                $voucer=new Voucer;
                $voucer->date= strtotime($request->date);
                $voucer->transaction_name="Supplier Payment";
                $voucer->v_inv_id= $v_invoice->id;
                $voucer->credit=$request->ammount;
                $voucer->debit=0;
                $voucer->ledger_id=$ledger->id;
                $voucer->subledger_id=$data['bank'];
                $voucer->author_id= auth()->user()->id;
                $voucer->save();
                if($voucer){
                    return response()->json(['message'=>'Supplier Payment Added']);
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
        $vinvoice=Vinvoice::where('id',$id)->first();
        if($vinvoice->action_type!=2){
            return redirect(URL::to('admin/s-payment'));
        }
        $bank_ledger=AccountLedger::where('name','Bank')->first()->id;
        $invoice=DB::select("
            SELECT voucers.id,voucers.ledger_id,voucers.subledger_id,voucers.date,cheque_no,cheque_status,cheque_issue_date,voucers.v_inv_id,concat(account_ledgers.code,if(account_ledgers.code<>'','-',''),account_ledgers.name) name,account_ledgers.name ledger_name,voucers.debit,voucers.credit,voucers.comment,
            ## concat(ifnull(customers.id,''),ifnull(suppliers.id,''),ifnull(banks.id,''),ifnull(account_subledgers.id,''),ifnull(employees.id,'')) sub_id,
            concat(ifnull(customers.code,''),ifnull(suppliers.code,''),ifnull(banks.code,''),ifnull(account_subledgers.code,''),ifnull(employees.code,'')) sub_code,
            concat(ifnull(customers.name,''),ifnull(suppliers.name,''),ifnull(banks.name,''),ifnull(account_subledgers.name,''),ifnull(employees.name,'')) sub_name
            from voucers
            inner join account_ledgers on voucers.ledger_id=account_ledgers.id
            left join account_subledgers on (account_ledgers.sub_account=1 and account_ledgers.id=account_subledgers.ledger_id and account_subledgers.id=voucers.subledger_id)
            left join suppliers on account_ledgers.sub_account=0 and account_ledgers.relation_with='suppliers' and suppliers.id=voucers.subledger_id
            left join customers on account_ledgers.sub_account=0 and account_ledgers.relation_with='customers' and customers.id=voucers.subledger_id
            left join banks on account_ledgers.sub_account=0 and account_ledgers.relation_with='banks' and voucers.subledger_id=banks.id
            left join employees on account_ledgers.sub_account=0 and account_ledgers.relation_with='employees' and voucers.subledger_id=employees.id
            where voucers.v_inv_id=:id 
            order by voucers.id
        ",['id'=>$id]);
        // dd($invoice);
        return response()->json(['vinvoice'=>$vinvoice,'voucer'=>$invoice]);
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
        $data=$request->all();
        if($data['supplier']=="null"){
            $data['supplier']=null;
        }
        if($data['bank']=="null"){
            $data['bank']=null;
        }
        if($data['method']==0){
            $bank_cond='nullable';
        }else{
            $bank_cond="required";
        }
        $validator=Validator::make($data,[
            'supplier'=>"required|max:200",
            'ammount'=>["required",new ZeroValidationRule],
            'date'=>"required|max:200",
            'method'=>"required|max:1",
            'note'=>"nullable|max:500",
            'bank'=>$bank_cond."|max:500",
        ]);

        if($validator->passes()){
            $customer_ledger=AccountLedger::where('name','Supplier')->first();
            $v_invoice=Vinvoice::find($id);
            $v_invoice->date=strtotime($request->date);
            $v_invoice->total=$request->ammount;
            $v_invoice->note=$request->note;
            $v_invoice->supplier_id=$request->supplier;
            // action type 2 for supplier payment
            $v_invoice->action_type=2;
            $v_invoice->author_id = auth()->user()->id;
            $v_invoice->save();
            if($v_invoice){
                // customer 
                $voucer=Voucer::find($request->v_id_cus);
                $voucer->date= strtotime($request->date);
                $voucer->transaction_name="Supplier Payment";
                $voucer->v_inv_id= $v_invoice->id;
                $voucer->debit=$request->ammount;
                $voucer->ledger_id=$customer_ledger->id;
                $voucer->subledger_id=$request->supplier;
                $voucer->author_id= auth()->user()->id;
                $voucer->save();
                // Cash/Bank dabit
                if($request->method==0){
                    $ledger=AccountLedger::where('name','Cash')->first();
                }else{
                    $ledger=AccountLedger::where('name','Bank')->first();
                }
                $voucer=Voucer::find($request->v_id_method);
                $voucer->date= strtotime($request->date);
                $voucer->transaction_name="Supplier Payment";
                $voucer->v_inv_id= $v_invoice->id;
                $voucer->credit=$request->ammount;
                $voucer->ledger_id=$ledger->id;
                if($request->bank=='null'){
                    $request->bank=null;
                }
                $voucer->subledger_id=$request->bank;
                if($request->method==1){
                    $voucer->cheque_no=$request->cheque_no;
                    $voucer->cheque_issue_date=strtotime($request->issue_date);
                    // $voucer->cheque_photo=$data['cheque_no'];
                }
                $voucer->author_id= auth()->user()->id;
                $voucer->save();
                if($voucer){
                    return response()->json(['message'=>'Supplier Payment Updated']);
                }
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
        $inv=Vinvoice::where('id',$id)->first();
        if($inv->action_type==2){
            $deleteInv=Vinvoice::where('id',$id)->delete();
            if($deleteInv){
                $deleteVoucer=Voucer::where('v_inv_id',$id)->delete();
                return response()->json(['message'=>"Receive Deleted Success"]);
            }
        }
        return response()->json(['error'=>"Something else"]);
    }
}
