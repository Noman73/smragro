<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountGroup;
use Validator;
use DataTables;
class AccountGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Account Group View',['only'=>'index']);
        $this->middleware('permission:Account Group Create',['only'=>'store']);
        $this->middleware('permission:Account Group Edit',['only'=>'edit']);
        $this->middleware('permission:Account Group Edit',['only'=>'update']);
        $this->middleware('permission:Account Group Delete',['only'=>'destroy']);
    }
    public function index()
    {
        if(request()->ajax()){
            $get=AccountGroup::with('classes')->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('group.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('group.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              
              $button.='</div>';
            return $button;
          })
          ->addColumn('code_range',function($get){
            return $get->start_code.'-'.$get->end_code;
        })
        ->addColumn('class',function($get){
            return $get->classes->name;
        })
          ->rawColumns(['action'])->make(true);
        }
        return view('backend.accounts.accounts_group.accounts_group');
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
        $validator=Validator::make($request->all(),[
            'name'=>"required|max:200|min:1|unique:account_groups,name",
            'class'=>"required|max:200|min:1",
            'start_code'=>"required|max:200|min:1",
            'end_code'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
            $class=new AccountGroup;
            $class->name=$request->name;
            $class->class_id=$request->class;
            $class->start_code=$request->start_code;
            $class->end_code=$request->end_code;
            $class->author_id=auth()->user()->id;
            $class->save();
            if ($class) {
                return response()->json(['message'=>'Account Group Added Success']);
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
        return response()->json(AccountGroup::with('classes')->find($id));
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
        // group name cannot be edit
        $validator=Validator::make($request->all(),[
            'name'=>"required|max:200|min:1",
            'class'=>"required|max:200|min:1",
            'start_code'=>"required|max:200|min:1",
            'end_code'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
            $class=AccountGroup::find($id);
            $class->class_id=$request->class;
            $class->start_code=$request->start_code;
            $class->end_code=$request->end_code;
            $class->author_id=auth()->user()->id;
            $class->save();
            if ($class) {
                return response()->json(['message'=>'Account Group Added Success']);
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

    public function getAccountGroup(Request $request){
        $group= AccountGroup::where('name','like','%'.$request->searchTerm.'%')->take(15)->get();
        foreach ($group as $value){
             $set_data[]=['id'=>$value->id,'text'=>$value->name];
         }
        return $set_data;
     }
}
