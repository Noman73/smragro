<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmsTemplate;
use DataTables;
use Validator;
class SmsTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $get=SmsTemplate::query();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('sms_template.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('sms_template.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          ->addColumn('status',function($get){
            return ($get->status==1? "<span class='bg-success p-1 rounded'>Active</span>" : "<span class='bg-danger p-1 rounded'>Deactive</span>");
          })
          ->rawColumns(['status','action'])->make(true);
        }
        return view('backend.sms_template.sms_template');
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
            'sms'=>"required|max:200|min:1",
            'area'=>"required|max:200|min:1",
            'status'=>"required|max:1|min:1",
        ]);
        if($validator->passes()){
            $sms=SmsTemplate::where('area',$request->area)->first();
            if($sms==null){
                $sms=new SmsTemplate;
            }
            $sms->sms=$request->sms;
            $sms->area=$request->area;
            $sms->status=$request->status;
            $sms->author_id=auth()->user()->id;
            $sms->save();
            if ($sms) {
                return response()->json(['message'=>'Sms Template Added Success']);
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
        return response()->json(SmsTemplate::find($id));
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
