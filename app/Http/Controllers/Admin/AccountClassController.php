<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classes;
use Validator;
use DataTables;

class AccountClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Account Class View',['only'=>'index']);
        $this->middleware('permission:Account Class Create',['only'=>'store']);
        $this->middleware('permission:Account Class Edit',['only'=>'edit']);
        $this->middleware('permission:Account Class Edit',['only'=>'update']);
        $this->middleware('permission:Account Class Delete',['only'=>'destroy']);
    }
    public function index()
    {
        if(request()->ajax()){
            $get=Classes::query();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('classes.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('classes.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              
              $button.='</div>';
            return $button;
          })
          ->rawColumns(['action'])->make(true);
        }
        return view('backend.accounts.classes.classes');
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
            'class_type'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
            $class=new Classes;
            $class->name=$request->name;
            $class->class_type=$request->class_type;
            $class->author_id=auth()->user()->id;
            $class->save();
            if ($class) {
                return response()->json(['message'=>'Account Class Added Success']);
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
        return response()->json(Classes::find($id));
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
        $validator=Validator::make($request->all(),[
            'name'=>"required|max:200|min:1",
            'class_type'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
            $class=Classes::find($id);
            $class->name=$request->name;
            $class->class_type=$request->class_type;
            $class->author_id=auth()->user()->id;
            $class->save();
            if ($class) {
                return response()->json(['message'=>'Account Class Updated Success']);
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
        $delete=Classes::where('id',$id)->delete();
        if ($delete) {
            return response()->json(['message'=>'Category Deleted']);
        }else{
            return response()->json(['warning'=>'Something Wrong']);
        }
    }
    public function getClass(Request $request){
        $classes= Classes::where('name','like','%'.$request->searchTerm.'%')->take(15)->get();
        foreach ($classes as $value){
             $set_data[]=['id'=>$value->id,'text'=>$value->name];
         }
         return $set_data;
     }
}
