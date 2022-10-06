<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Part;
use Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class ApplyPermissionController extends Controller
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
        return view('backend.authorization.asign_permission.asign_permission',compact('permission'));
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
        $data['role']=explode(',', $request->role);
        $data['condition']=explode(',', $request->condition);
        $validator=Validator::make($data,[
        'role'=>'required|array',
        'role.*'=>'required|max:50',
        'permission'=>'required|array',
        'permission.*'=>'required|max:100|min:1',
     ]);
     if ($validator->passes()) {
        for ($i=0; $i <count($data['role']) ; $i++) { 
            $role=Role::findByName($data['role'][$i]);
            if($data['condition'][$i]=='true'){
                $role->givePermissionTo($data['permission'][$i]);
            }else{
                $permission=Permission::findByName($data['permission'][$i]);
                $permission->removeRole($role);
            }
        }

        // old code
        //  for ($i=0; $i <count($r->role) ; $i++) {
        //     $role=Role::findById($r->role[$i]['id']);
        //     for ($i2=0; $i2 <count($r->permission) ; $i2++) { 
        //        if ($r->array[$i2][$i]=='on') {
        //          $permission=Permission::findById($r->permission[$i2]['id']);
        //          $role->givePermissionTO($permission);
        //        }elseif ($r->array[$i2][$i]=='off') {
        //          $permission=Permission::findById($r->permission[$i2]['id']);
        //          $permission->removeRole($role);
        //        }
        //     }
        //  }
         return response()->json(['message'=>'Permission Assign Success']);
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
}
