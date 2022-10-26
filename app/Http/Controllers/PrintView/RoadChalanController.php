<?php

namespace App\Http\Controllers\PrintView;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoadChalan;
use App\Models\Invoice;
use URL;
use Validator;
class RoadChalanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'kartoon'=>"required|max:200|min:1",
            'bundle'=>"required|max:200|min:1",
            'sack'=>"required|max:200|min:1",
            'box'=>"required|max:200|min:1",
        ]);

        if($validator->passes()){
            $road_chalan=new RoadChalan;
            $road_chalan->invoice_id=$request->invoice_id;
            $road_chalan->kartoon=$request->kartoon;
            $road_chalan->bundle=$request->bundle;
            $road_chalan->sack=$request->sack;
            $road_chalan->box=$request->box;
            $road_chalan->author_id=auth()->user()->id;
            $road_chalan->save();
            if($road_chalan){
                return redirect(route('road_chalan.show',$request->invoice_id))->with(['data'=>$road_chalan]);
            }
        }
        return redirect(URL::to("admin/view-pages/sales-road-chalan-invoice-print/".$request->invoice_id))->with('error',$validator->getMessageBag());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice=Invoice::with('sales','customer','pay','condition_amount','user','shipping_customer','courier','notes','road_chalan')->where('id',$id)->first();
        return view('backend.view_pages.invoices.road_chalan.print',compact('invoice'));
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
