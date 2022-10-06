<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Validator;
use DataTables;
use URL;
class RegularConditionListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Regular Condition View',['only'=>'index']);
    }
    public function index()
    {
        // return $get=Invoice::with('customer')->get();

        if(request()->ajax()){
            $get=Invoice::with('customer','user')->where('sale_by',2)->orderBy('dates','desc')->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
              $button.='<a href="'.URL::to('admin/view-pages/sales-invoice/'.$get->id).'" class="btn btn-warning shadow btn-xs sharp me-1"><i class="fas fa-print"></i></a>
              <a href="'.route('invoice.edit',$get->id).'" class="btn btn-primary shadow btn-xs sharp ml-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-id="'.$get->id.'" class="btn btn-primary shadow btn-xs sharp ml-1 receive">pay</a>

              <a data-url="'.route('invoice.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          ->addColumn('customer_name',function($get){
            return ($get->customer!=null ? $get->customer->name: 'not found');
        })
        ->addColumn('invoice_type',function($get){
            switch ($get->sale_type) {
                case 0:
                    return "Cash Sale";
                    break;
                case 1:
                    return "Regular Sale";
                case 2:
                    return "Condition Sale";
                    break;
            }
        })
        ->addColumn('shipping_to',function($get){
            return $get->shipping_customer->name;
        })
        ->addColumn('courier',function($get){
            return $get->courier->name;
        })
        ->addColumn('user_name',function($get){
            return $get->user->name;
        })
        ->addColumn('dates',function($get){
            return date('d-m-Y',intval($get->dates));
        })
        ->addColumn('id',function($get){
            return 'S-'.date('dm',$get->dates).substr(date('Y',$get->dates),2).$get->id;
        })
          ->rawColumns(['action'])->make(true);
        }
        return view('backend.invoice.regular_condition_list');
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
