<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeSalary;
use Validator;
use DataTables;
class EmployeeSalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $get=EmployeeSalary::with('employee')->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('employee-salary.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('employee-salary.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          ->addColumn('name',function($get){
            return $get->employee->code.'-'.$get->employee->name;
          })
          ->rawColumns(['action'])->make(true);
        }
        return view('backend.employee_salary.employee_salary');
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
            'employee'=>"required|max:15|min:1",
            'amount'=>"required|max:15|min:1",
            'month'=>"required|max:15|min:1",
        ]);
        if($validator->passes()){
            $salary=new EmployeeSalary;
            $salary->employee_id=$request->employee;
            $salary->month=$request->month;
            $salary->amount=$request->amount;
            $salary->author_id=auth()->user()->id;
            $salary->save();
            if ($salary) {
                return response()->json(['message'=>'Salary Added Success']);
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
        return response()->json(EmployeeSalary::with('employee')->find($id));
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
            'employee'=>"required|max:15|min:1",
            'amount'=>"required|max:15|min:1",
            'month'=>"required|max:15|min:1",
        ]);
        if($validator->passes()){
            $salary=EmployeeSalary::find($id);
            $salary->employee_id=$request->employee;
            $salary->month=$request->month;
            $salary->amount=$request->amount;
            $salary->author_id=auth()->user()->id;
            $salary->save();
            if ($salary) {
                return response()->json(['message'=>'Salary Updated Success']);
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
