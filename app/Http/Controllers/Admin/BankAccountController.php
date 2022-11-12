<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use DataTables;
use Validator;
use App\Models\AccountLedger;
class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Bank View',['only'=>'index']);
        $this->middleware('permission:Bank Create',['only'=>'store']);
        $this->middleware('permission:Bank Edit',['only'=>'edit']);
        $this->middleware('permission:Bank Edit',['only'=>'update']);
        $this->middleware('permission:Bank Delete',['only'=>'destroy']);
    }
    public function index()
    {
        if(request()->ajax()){
            $get=Bank::query();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('bank.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('bank.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              
              $button.='</div>';
            return $button;
          })
          ->rawColumns(['action'])->make(true);
        }
        return view('backend.bank.bank');
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
            'name'=>"required|max:200|min:1",
            'branch_name'=>"nullable|max:200|min:1",
            'account_no'=>"required|max:200|min:1",
            'account_code'=>"nullable|max:200|min:1",
            'details'=>"nullable|max:200|min:1",
            'opening_balance'=>"nullable|max:200|min:1",
            'account_type'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
            $bank_ledger=AccountLedger::where('name','Bank')->first();
            $count=Bank::count();
            $bank=new Bank;
            $bank->name=$request->name;
            $bank->branch_name=$request->branch_name;
            $bank->account_no=$request->account_no;
            $bank->account_code=$request->account_code;
            $bank->details=$request->details;
            $bank->open_ammount=$request->opening_balance;
            $bank->account_type=$request->account_type;
            $bank->code=$bank_ledger->code.'-'.$count+1;
            $bank->author_id=auth()->user()->id;
            $bank->save();
            if ($bank) {
                return response()->json(['message'=>'Bank Added Success']);
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
        return response()->json(Bank::find($id));
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
            'name'=>"required|max:200|min:1",
            'branch_name'=>"nullable|max:200|min:1",
            'account_no'=>"required|max:200|min:1",
            'account_code'=>"nullable|max:200|min:1",
            'details'=>"nullable|max:200|min:1",
            'opening_balance'=>"nullable|max:200|min:1",
            'account_type'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
            $category=Bank::find($id);
            $category->name=$request->name;
            $category->branch_name=$request->branch_name;
            $category->account_no=$request->account_no;
            $category->account_code=$request->account_code;
            $category->open_ammount=$request->opening_balance;
            $category->details=$request->details;
            $category->account_type=$request->account_type;
            $category->author_id=auth()->user()->id;
            $category->save();
            if ($category) {
                return response()->json(['message'=>'Bank Added Success']);
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

    public function getPaymentMethod(Request $request)
    {
        $payment_method= Bank::where('name','like','%'.$request->searchTerm.'%')->take(15)->get();
        foreach ($payment_method as $value){
             $set_data[]=['id'=>$value->id,'text'=>$value->name];
        }
        return $set_data;
    }

    public function getBankDetails($id)
    {
        $data=Bank::where('id',$id)->first();
        return response()->json($data);
    }
}
