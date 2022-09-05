<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Models\AccountGroup;
use DataTables;
use App\Rules\RelationWithRule;
use Validator;
use DB;
class AccountLedgerController extends Controller
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
            $get=AccountLedger::with('group')->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('account-ledger.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('account-ledger.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              
              $button.='</div>';
            return $button;
          })
          ->addColumn('group',function($get){
          return $get->group->name;
        })
          ->rawColumns(['action'])->make(true);
        }
        return view('backend.accounts.ledger.ledger');
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
        if($request->account_group=='null'){
            $data['account_group']=null;
        }
        $validator=Validator::make($data,[
            'name'=>"required|max:200|min:1|unique:account_ledgers,name",
            'account_group'=>"required|max:200|min:1",
            'status'=>"required|max:1|min:1",
            // 'relation_with'=>["nullable","max:200","min:1",new RelationWithRule],
        ]);
        if($validator->passes()){
            $group= AccountGroup::where('id',$data['account_group'])->first();
            $groups= AccountLedger::where('group_id',$data['account_group'])->get();
            $class=new AccountLedger;
            $class->name=$data['name'];
            $class->group_id=$data['account_group'];
            $class->code=($group->start_code)+intval($groups->count())+1;
            $class->sub_account=$data['sub_account'];
            $class->author_id=auth()->user()->id;
            $class->status=$data['status'];
            $class->save();
            if ($class) {
                return response()->json(['message'=>'Account Ledger Added Success','code'=>$class->code]);
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json(AccountLedger::with('group')->find($id));
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
        // return response()->json(['message'=>'Account Ledger Cannot Edit']);
        $validator=Validator::make($request->all(),[
            'name'=>"required|max:200|min:1|unique:account_ledgers,name,".$id,
            'account_group'=>"required|max:200|min:1",
            'status'=>"required|max:1|min:1",
            // 'relation_with'=>["nullable","max:200","min:1",new RelationWithRule],
        ]);
        if($validator->passes()){
            $class=AccountLedger::find($id);
            // $class->name=$request->name;
            $class->group_id=$request->account_group;
            // $class->relation_with=$request->relation_with;
            $class->author_id=auth()->user()->id;
            $class->status=$request->status;
            $class->save();
            if ($class) {
                return response()->json(['message'=>'Account Ledger Updated Success']);
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

    public function getAccountLedger(Request $request){

        $ledger= AccountLedger::where('name','like','%'.$request->searchTerm.'%')->orWhere('code','like','%'.$request->searchTerm.'%')->where('status',1)->take(15)->get();
        foreach ($ledger as $value){
             $set_data[]=['id'=>$value->id,'text'=>($value->code!=null? $value->code.'-' :'').$value->name];
         }
        return $set_data;
     }
     public function getAccountSubLedger(Request $request)
     {
        // return $request->all();
        $data=AccountLedger::where('id',$request->ledger_id)->first();
        if($data->relation_with!=null){
            $relation=DB::select('select id,name,code from '.$data->relation_with.' where name like :key or code like :key2',['key'=>'%'.$request->searchTerm.'%','key2'=>'%'.$request->searchTerm.'%']);
            foreach ($relation as $value){
                $set_data[]=['id'=>$value->id,'text'=>($value->code!=null ? $value->code.'-':'').$value->name];
            }
        }elseif($data->relation_with==null and $data->sub_account==1){
            $relation=DB::select('select id,name,code from account_subledgers where name like :key or code like :key2 and ledger_id=:ledger_id',['key'=>'%'.$request->searchTerm.'%','key'=>'%'.$request->searchTerm.'%','ledger_id'=>$request->ledger_id]);
            foreach ($relation as $value){
                $set_data[]=['id'=>$value->id,'text'=>($value->code!=null ? $value->code.'-':'').$value->name];
            }
        }
        return $set_data;
     }
     public function getAccountLedgerCanSubAccount(Request $request){
        $ledger= AccountLedger::where('name','like','%'.$request->searchTerm.'%')->where('sub_account',1)->take(15)->get();
        foreach ($ledger as $value){
             $set_data[]=['id'=>$value->id,'text'=>$value->name];
        }
        return $set_data;
     }
}

