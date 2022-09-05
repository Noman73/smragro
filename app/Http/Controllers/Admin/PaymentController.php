<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucer;
use App\Models\AccountLedger;
use App\Models\Vinvoice;
use App\Rules\CashCheckRule;
use App\Rules\ZeroValidationRule;
use Validator;
use DataTables;
use URL;
use DB;
class PaymentController extends Controller
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
        if(request()->ajax()){
            $get=Vinvoice::where('action_type',0)->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url=""  href="'.URL::to('admin/view-pages/payment-view/'.$get->id).'" class="btn btn-warning shadow btn-xs sharp me-1 "><i class="fas fa-print"></i></a>';
                $button.='<a data-url="'.route('payment.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 ml-1 editRow"><i class="fas fa-pen"></i></a>
              <a data-url="'.route('payment.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
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
        return view('backend.payment.payment');
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
        // return response()->json($request->all());
        $data=$request->all();
        $data['ledger']= explode(',', $request->ledger);
        $data['subledger']= explode(',', $request->subledger);
        $data['ammount']= explode(',', $request->ammount);
        $data['comment']= explode(',', $request->comment);
        $data['date']= $request->date;
        if($data['method']==0){
            $bank_cond='nullable';
        }else{
            $bank_cond="required";
        }
        $validator=Validator::make($data,[
            'ledger'=>"required|array|max:200",
            'subledger'=>"required|array|max:200",
            'ammount'=>["required","array","max:200",new CashCheckRule($data['ledger'])],
            'ammount.*'=>["required","max:200",new ZeroValidationRule],
            'comment'=>"required|array|max:200",
            'date'=>"required|max:200",
            'note'=>"nullable|max:500",
        ]);
        if($validator->passes()){
                $total=0;
                $i=0;
                foreach($data['ammount'] as $value){
                    $total=$total+floatval($value);
                }
                // return $total;
                $v_invoice=new Vinvoice;
                $v_invoice->date=strtotime($data['date']);
                $v_invoice->total=$total;
                $v_invoice->note=$data['note'];
                // action type 0 for payment 
                $v_invoice->action_type=0;
                $v_invoice->author_id = auth()->user()->id;
                $v_invoice->save();
                if($v_invoice){
                    foreach($data['ledger'] as $value){
                        // return $data['ammount'][$i];
                        $voucer=new Voucer;
                        $voucer->date= strtotime($data['date']);
                        $voucer->transaction_name="Payment";
                        $voucer->v_inv_id= $v_invoice->id;
                        $voucer->debit=$data['ammount'][$i];
                        $voucer->ledger_id=$data['ledger'][$i];
                        $voucer->subledger_id=($data['subledger'][$i] == 'null'? null : $data['subledger'][$i]);
                        $voucer->comment=$data['comment'][$i];
                        $voucer->author_id = auth()->user()->id;
                        $voucer->save();
                        // $total=$total+floatval($data['ammount'][$i]);
                        $i=$i+1;
                    }   
                    if($data['method']==0){
                        $ledger=AccountLedger::where('name','Cash')->first();
                    }else{
                        $ledger=AccountLedger::where('name','Bank')->first();
                    }
                        $voucer=new Voucer;
                        $voucer->date= strtotime($data['date']);
                        $voucer->transaction_name="Payment";
                        $voucer->v_inv_id= $v_invoice->id;
                        $voucer->credit=$total;
                        $voucer->ledger_id=$ledger->id;
                        if($data['bank']=='null'){
                            $data['bank']=null;
                        }
                        if($data['method']==1){
                            $voucer->cheque_no=$data['cheque_no'];
                            $voucer->cheque_issue_date=$data['issue_date'];
                            // $voucer->cheque_photo=$data['cheque_no'];
                        }
                        $voucer->subledger_id=$data['bank'];
                        $voucer->author_id= auth()->user()->id;
                        $voucer->save();
                }
                return response()->json(['message'=>'Payment Successfully Added']);    
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
        if($vinvoice->action_type!=0){
            return false;
        }
        $bank_ledger=AccountLedger::where('name','Bank')->first()->id;
        $invoice=DB::select("
            SELECT voucers.id,voucers.ledger_id,voucers.subledger_id,voucers.date,cheque_no,cheque_status,cheque_issue_date,voucers.v_inv_id,concat(account_ledgers.code,if(account_ledgers.code<>'','-',''),account_ledgers.name) name,voucers.debit,voucers.credit,voucers.comment,
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
         // return response()->json($request->all());
         $data=$request->all();
         $data['ledger']= explode(',', $request->ledger);
         $data['subledger']= explode(',', $request->subledger);
         $data['ammount']= explode(',', $request->ammount);
         $data['comment']= explode(',', $request->comment);
         $data['date']= $request->date;
         if($data['method']==0){
             $bank_cond='nullable';
         }else{
             $bank_cond="required";
         }
         $validator=Validator::make($data,[
             'ledger'=>"required|array|max:200",
             'subledger'=>"required|array|max:200",
             'ammount'=>["required","array","max:200",new CashCheckRule($data['ledger'])],
             'ammount.*'=>["required","max:200",new ZeroValidationRule],
             'comment'=>"required|array|max:200",
             'date'=>"required|max:200",
             'note'=>"nullable|max:500",
         ]);
         if($validator->passes()){
                 $total=0;
                 $i=0;
                 foreach($data['ammount'] as $value){
                     $total=$total+floatval($value);
                 }
                 // return $total;
                 $v_invoice=Vinvoice::find($id);
                 $v_invoice->date=strtotime($data['date']);
                 $v_invoice->total=$total;
                 $v_invoice->note=$data['note'];
                 // action type 0 for payment 
                 $v_invoice->action_type=0;
                 $v_invoice->author_id = auth()->user()->id;
                 $v_invoice->save();
                 if($v_invoice){
                     foreach($data['ledger'] as $value){
                         // return $data['ammount'][$i];
                         if($data['v_id'][$i]!=0){
                            $voucer=Voucer::find($data['v_id'][$i]);
                            $voucer->date= strtotime($data['date']);
                            $voucer->transaction_name="Payment";
                            $voucer->v_inv_id= $v_invoice->id;
                            $voucer->debit=$data['ammount'][$i];
                            $voucer->ledger_id=$data['ledger'][$i];
                            $voucer->subledger_id=($data['subledger'][$i] == 'null'? null : $data['subledger'][$i]);
                            $voucer->comment=$data['comment'][$i];
                            $voucer->author_id = auth()->user()->id;
                            $voucer->save();
                            // $total=$total+floatval($data['ammount'][$i]);
                            $i=$i+1;
                         }else{
                            $voucer=new Voucer;
                            $voucer->date= strtotime($data['date']);
                            $voucer->transaction_name="Payment";
                            $voucer->v_inv_id= $v_invoice->id;
                            $voucer->debit=$data['ammount'][$i];
                            $voucer->ledger_id=$data['ledger'][$i];
                            $voucer->subledger_id=($data['subledger'][$i] == 'null'? null : $data['subledger'][$i]);
                            $voucer->comment=$data['comment'][$i];
                            $voucer->author_id = auth()->user()->id;
                            $voucer->save();
                            // $total=$total+floatval($data['ammount'][$i]);
                            $i=$i+1;
                         }
                     }   
                     if($data['method']==0){
                         $ledger=AccountLedger::where('name','Cash')->first();
                     }else{
                         $ledger=AccountLedger::where('name','Bank')->first();
                     }
                         $voucer=Voucer::find($request->method_voucer);
                         $voucer->date= strtotime($data['date']);
                         $voucer->transaction_name="Payment";
                         $voucer->v_inv_id= $v_invoice->id;
                         $voucer->credit=$total;
                         $voucer->ledger_id=$ledger->id;
                         if($data['bank']=='null'){
                             $data['bank']=null;
                         }
                         if($data['method']==1){
                             $voucer->cheque_no=$data['cheque_no'];
                             $voucer->cheque_issue_date=$data['issue_date'];
                             // $voucer->cheque_photo=$data['cheque_no'];
                         }
                         $voucer->subledger_id=$data['bank'];
                         $voucer->author_id= auth()->user()->id;
                         $voucer->save();
                 }
                 return response()->json(['message'=>'Payment Successfully Added']);    
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
