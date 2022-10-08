<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use DataTables;
use DB;
use App\Models\Classes;
class ChartOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('permission:Chart Of Account View',['only'=>'index']);
    }
    public function index()
    {

        // return Classes::with('groups')->get();
        $query="SELECT classes.class_type,classes.name class_name,account_groups.name group_name,account_ledgers.name,account_ledgers.code,ifnull(sum(voucers.debit),0) debit,ifnull(sum(voucers.credit),0.00) credit  from account_ledgers 
        left join voucers on voucers.ledger_id=account_ledgers.id
        inner join account_groups on account_ledgers.group_id=account_groups.id
        inner join classes on account_groups.class_id=classes.id
        group by account_ledgers.id,account_ledgers.name,account_groups.name,classes.name
        order by classes.id,account_groups.id,account_ledgers.id
       ";
        $data=DB::select($query);
        // return array_unique($data);
        if(request()->ajax()){
            $get=DB::select($query);
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('balance',function($get){
                
              if($get->class_type==1 || $get->class_type==4){
                return floatval($get->debit)-floatval($get->credit);
              }else{
                return floatval($get->credit)-floatval($get->debit);
              }
            })
              ->make(true);
        }
        return view('backend.accounts.chartofaccount.chartofaccount',compact($data));
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
        //
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
