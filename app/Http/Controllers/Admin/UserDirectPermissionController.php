<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Part;
use Validator;
use App\Models\User;
class UserDirectPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        
        $this->middleware('auth');
        $this->middleware('role:Super-Admin',['only'=>'index']);
        $this->middleware('role:Super-Admin',['only'=>'store']);
        $this->middleware('role:Super-Admin',['only'=>'edit']);
        $this->middleware('role:Super-Admin',['only'=>'update']);
        $this->middleware('role:Super-Admin',['only'=>'destroy']);
    }
    public function index()
    {
        $permission=Part::with('permission')->orderBy('name','asc')->get();
        return view('backend.authorization.asign_permission.asign_permission_user',compact('permission'));
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
        $data['permission']=explode(',', $request->permission);
        $data['condition']=explode(',', $request->condition);
        $validator=Validator::make($data,[
        'user'=>'required|max:20|min:1',
        'permission'=>'required|array',
        'permission.*'=>'required|max:100|min:1',
     ]);
     if ($validator->passes()) {
        for ($i=0; $i <count($data['permission']) ; $i++) { 
            $user=User::find($request->user);
            if($data['condition'][$i]=='true'){
                $user->givePermissionTo($data['permission'][$i]);
            }else{
                $user->revokePermissionTo($data['permission'][$i]);
            }
        }
         return response()->json(['message'=>'Permission Assign Success']);
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
