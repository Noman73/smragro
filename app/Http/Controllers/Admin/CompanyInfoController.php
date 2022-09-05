<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DataTables;
use App\Models\CompanyInformations;
use Storage;
class CompanyInfoController extends Controller
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

        $info=CompanyInformations::first();
        return view('backend.setting.general_info.general_info',compact('info'));

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
            'bin_no'=>"required|max:200|min:1",
            'company_name'=>"required|max:200|min:1",
            'company_slogan'=>"required|max:200|min:1",
            'adress'=>"required|max:200|min:1",
            'phone'=>"required|max:200|min:1",
            'email'=>"required|max:200|min:1",
            'web'=>"required|max:200|min:1",
            'logo'=>"nullable|max:1024|mimes:jpeg,png,gif,svg",
            'icon'=>"nullable|max:1024|mimes:jpeg,png,gif,svg",
        ]);
        if($validator->passes()){
            $exists=CompanyInformations::count();
            if($exists>0){
                $company=CompanyInformations::first();
                if($request->hasFile('logo') && $company->logo!=null){
                    info(storage_path());
                    unlink(storage_path('app/public/logo/'.$company->logo));
                }
                if($request->hasFile('icon') && $company->icon!=null){
                    info(storage_path());
                    unlink(storage_path('app/public/icon/'.$company->icon));
                }
            }else{
                $company=new CompanyInformations;
            }
            $company->bin_no=$request->bin_no;
            $company->company_name=$request->company_name;
            $company->company_slogan=$request->company_slogan;
            $company->adress=$request->adress;
            $company->phone=$request->phone;
            $company->email=$request->email;
            $company->web=$request->web;
            $company->author_id=auth()->user()->id;
            if ($request->hasFile('logo')) {
                $ext = $request->logo->getClientOriginalExtension();
                $name =auth()->user()->id  .'_'. time() . '.' . $ext;
                $request->logo->storeAs('public/logo', $name);
                $company->logo = $name;
            }
            if ($request->hasFile('icon')) {
                $ext = $request->icon->getClientOriginalExtension();
                $name =auth()->user()->id  .'_'. time() . '.' . $ext;
                $request->icon->storeAs('public/icon', $name);
                $company->icon = $name;
            }
            $company->save();
            if ($company) {
                return response()->json(['message'=>'Company Info Updated Success']);
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
