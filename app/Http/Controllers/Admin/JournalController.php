<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DataTables;
use App\Models\InvoiceJournal;
use App\Models\Voucer;
use App\Rules\JournalSubledgerRule;
use URL;
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
    }
    public function index()
    {
        if(request()->ajax()){
            $get=InvoiceJournal::with('ledger')->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url=""  href="'.URL::to('admin/view-pages/journal-view/'.$get->id).'" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('account-ledger.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              
              $button.='</div>';
            return $button;
          })
          ->addColumn('date',function($get){
          return date('d-m-Y',$get->date);
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
                    $journal->save();
                    $i=$i+1;
                }
                return response()->json(['message'=>"Journal Added Success"]);
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
