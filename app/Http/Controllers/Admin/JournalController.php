<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DataTables;
use App\Models\InvoiceJournal;
use App\Models\Voucer;
use App\Models\AccountLedger;
use App\Rules\JournalSubledgerRule;
use URL;
use DB;
class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Journal View',['only'=>'index']);
        $this->middleware('permission:Journal Create',['only'=>'store']);
        $this->middleware('permission:Journal Edit',['only'=>'edit']);
        $this->middleware('permission:Journal Edit',['only'=>'update']);
        $this->middleware('permission:Journal Delete',['only'=>'destroy']);
    }
    public function index()
    {
        if(request()->ajax()){
            $get=InvoiceJournal::with('ledger')->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url=""  href="'.URL::to('admin/view-pages/journal-view/'.$get->id).'" class="btn btn-warning shadow btn-xs sharp me-1 mr-1"><i class="fas fa-eye"></i></a>
                <a href="javascript:void(0)"  data-url="'.route('journal.edit',$get->id).'" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('account-ledger.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              
              $button.='</div>';
            return $button;
          })
          ->addColumn('date',function($get){
          return date('d-m-Y',$get->date);
        })
        ->addColumn('trx_id',function($get){
            return 'J-'.date('dm',$get->date).substr(date('Y',$get->date),-2).$get->id;
        })
          ->rawColumns(['action'])->make(true);
        }
        return view('backend.accounts.journal.journal');
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
        $data['debit']= explode(',', $request->debit);
        $data['credit']= explode(',', $request->credit);
        $data['comment']= explode(',', $request->comment);
        $data['date']= $request->date;
        // return $data;
        $validator=Validator::make($data,[
            'ledger'=>"required|array|max:200",
            'subledger'=>["required","array","max:200",new JournalSubledgerRule($data['ledger'])],
            'debit'=>"required|array|max:200",
            'credit'=>"required|array|max:200",
            'comment'=>"required|array|max:200",
            'date'=>"required|max:200",
            'note'=>"nullable|max:200",
        ]);
        
        if($validator->passes()){
            $total=0;
            foreach($data['debit'] as $value){
                    if($value!=""){
                    $total+=$value;
                }
            }
            $inv=new InvoiceJournal;
            $inv->date=strtotime($data['date']);
            $inv->total=$total;
            $inv->note=$data['note'];
            $inv->author_id=auth()->user()->id;
            $inv->save();
            if($inv){
                $i=0;
                foreach($data['ledger'] as $value){
                    $journal=new Voucer;
                    $journal->date= strtotime($data['date']);
                    $journal->journal_inv_id=$inv->id;
                    $journal->transaction_name="journal";
                    $journal->ledger_id=$value;
                    $journal->subledger_id=($data['subledger'][$i] == 'null'? null : $data['subledger'][$i]);
                    $journal->debit=($data['debit'][$i]==''? 0 : $data['debit'][$i]);
                    $journal->credit=($data['credit'][$i]==''? 0 : $data['credit'][$i]);
                    $journal->comment=$data['comment'][$i];
                    $journal->author_id=auth()->user()->id;
                    $journal->save();
                    $i=$i+1;
                }
                return response()->json(['message'=>"Journal Added Success",'id'=>$inv->id]);
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
        $vinvoice=InvoiceJournal::where('id',$id)->first();
        
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
            where voucers.journal_inv_id=:id 
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
        $data['debit']= explode(',', $request->debit);
        $data['credit']= explode(',', $request->credit);
        $data['comment']= explode(',', $request->comment);
        $data['v_id']= explode(',', $request->v_id);
        $data['date']= $request->date;
        // return $data;
        $validator=Validator::make($data,[
            'ledger'=>"required|array|max:200",
            'subledger'=>["required","array","max:200",new JournalSubledgerRule($data['ledger'])],
            'debit'=>"required|array|max:200",
            'credit'=>"required|array|max:200",
            'comment'=>"required|array|max:200",
            'v_id'=>"required|array|max:200",
            'date'=>"required|max:200",
            'note'=>"nullable|max:200",
        ]);
        
        if($validator->passes()){
            $total=0;
            foreach($data['debit'] as $value){
                    if($value!=""){
                    $total+=$value;
                }
            }
            $inv=InvoiceJournal::find($id);
            $inv->date=strtotime($data['date']);
            $inv->total=$total;
            $inv->note=$data['note'];
            $inv->author_id=auth()->user()->id;
            $inv->save();
            if($inv){
                $i=0;
                foreach($data['ledger'] as $value){
                    if($data['v_id'][$i]!=0){
                    $journal=Voucer::find($data['v_id'][$i]);
                    $journal->date= strtotime($data['date']);
                    $journal->journal_inv_id=$inv->id;
                    $journal->transaction_name="journal";
                    $journal->ledger_id=$value;
                    $journal->subledger_id=($data['subledger'][$i] == 'null'? null : $data['subledger'][$i]);
                    $journal->debit=($data['debit'][$i]==''? 0 : $data['debit'][$i]);
                    $journal->credit=($data['credit'][$i]==''? 0 : $data['credit'][$i]);
                    $journal->comment=$data['comment'][$i];
                    // $journal->author_id=auth()->user()->id;
                    $journal->save();
                    }else{
                    $journal=new Voucer;
                    $journal->date= strtotime($data['date']);
                    $journal->journal_inv_id=$inv->id;
                    $journal->transaction_name="journal";
                    $journal->ledger_id=$value;
                    $journal->subledger_id=($data['subledger'][$i] == 'null'? null : $data['subledger'][$i]);
                    $journal->debit=($data['debit'][$i]==''? 0 : $data['debit'][$i]);
                    $journal->credit=($data['credit'][$i]==''? 0 : $data['credit'][$i]);
                    $journal->comment=$data['comment'][$i];
                    // $journal->author_id=auth()->user()->id;
                    $journal->save(); 
                    }
                    $i=$i+1;
                }
                return response()->json(['message'=>"Journal Updated Success",'id'=>$inv->id]);
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
