<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\ModelHasRole;
use DataTables;
use Validator;
class RoleAsignController extends Controller
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
    //     $user=User::find(1);
    //     $user->removeRole('Super-Admin');
        
        if(request()->ajax()){
            $get=ModelHasRole::with('role','user')->get();
            return DataTables::of($get)
              ->addIndexColumn()
             
          ->addColumn('role',function($get){
            return $get->role->name;
          })
          ->addColumn('user',function($get){
            return $get->user->name;
          })
          ->rawColumns(['role','user'])->make(true);
        }
        return view('backend.authorization.role_asign.role_asign');
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
        // $user=User::find($request->user);
        // return $role=Role::find($request->role);
        $validator=Validator::make($request->all(),[
            'role'=>'required|max:100|min:1',
            'user'=>'required|max:10|min:1',
          ]);
          if ($validator->passes()) {
            $user=User::find($request->user);
            $role=Role::find($request->role);
            $models=ModelHasRole::where('model_id',$user->id)->get();
            if ($user->hasAnyRole(Role::all())) {
                info('okkkkskskks');
                foreach($models as $model){
                    $roleName=Role::where('id',$model->role_id)->first()->name;
                    $user->removeRole($roleName);
                }
            }
            $user->assignRole(strval($request->role));
            return response()->json(['message'=>'Role Assign Success']);
         }
         return response()->json($validator->getMessageBag());
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

    public function getRole(Request $request)
    {
        $role= Role::where('name','like','%'.$request->searchTerm.'%')->take(15)->get();
        foreach ($role as $value){
             $set_data[]=['id'=>$value->id,'text'=>$value->name];
         }
         return $set_data; 
    }
    public function getUser(Request $request)
    {
        $role= User::where('name','like','%'.$request->searchTerm.'%')->take(15)->get();
        foreach ($role as $value){
             $set_data[]=['id'=>$value->id,'text'=>$value->name];
         }
         return $set_data; 
    }
}
