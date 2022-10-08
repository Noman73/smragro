<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Employee;
use App\Models\AccountLedger;
use DataTables;
class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Employee View',['only'=>'index']);
        $this->middleware('permission:Employee Create',['only'=>'store']);
        $this->middleware('permission:Employee Edit',['only'=>'edit']);
        $this->middleware('permission:Employee Edit',['only'=>'update']);
        $this->middleware('permission:Employee Delete',['only'=>'destroy']);
    }
    public function index()
    {
        if(request()->ajax()){
            $get=Employee::all();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
                $button ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('employee.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('employee.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          ->rawColumns(['action'])->make(true);
        }
        return view('backend.employee.employee');
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
        $validator=Validator::make($request->all(),[
            'name'=>"required|max:200|min:1",
            'email'=>"nullable|email|max:200|min:1",
            'phone'=>"required|max:200|min:1",
            'adress'=>"nullable|max:200|min:1",
            'birth_date'=>"nullable|max:200|min:1",
            'nid'=>"nullable|max:200|min:1",
            'experience'=>"nullable|max:200|min:1",
            'department'=>"nullable|max:200|min:1",
            'salary'=>"required|max:20|min:1",
            'image'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if($validator->passes()){
            $ledger_code=AccountLedger::where('name','Employee')->first();
            $countEmployee=Employee::count();
            $customer=new Employee;
            $customer->name=$request->name;
            $customer->email=$request->email;
            $customer->phone=$request->phone;
            $customer->code=$ledger_code->code.'-'.(($countEmployee ==null? 0 : $countEmployee)+1);
            $customer->adress=$request->adress;
            $customer->nid=$request->nid;
            $customer->birth_date=$request->birth_date;
            $customer->experience=$request->experience;
            $customer->department=$request->department;
            $customer->salary=$request->salary;
            $customer->author_id=auth()->user()->id;
            if ($request->hasFile('image')) {
                $ext = $request->image->getClientOriginalExtension();
                $name =auth()->user()->id  .'_'. time() . '.' . $ext;
                $request->image->storeAs('public/employee', $name);
                $customer->image = $name;
            }
            $customer->save();
            if ($customer) {
                return response()->json(['message'=>'Employee Added Success']);
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
    public function getEmployee(Request $request){
        $customer= Employee::where('name','like','%'.$request->searchTerm.'%')->orWhere('code','like','%'.$request->searchTerm.'%')->take(15)->get();
        foreach ($customer as $value){
             $set_data[]=['id'=>$value->id,'text'=>($value->code!=null? $value->code.'-': '').$value->name.'('.$value->phone.')'];
         }
         return $set_data;
     }
}
