<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MultiNote;
use DataTables;
use PhpParser\Node\Expr\AssignOp\Mul;
use Validator;
class MultiNoteController extends Controller
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
        if(request()->ajax()){
            $get=MultiNote::query();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('multi_note.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('multi_note.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          ->addColumn('status',function($get){
            return $get->status? "<span class='bg-success p-1 rounded'>Active</span>": "<span class='bg-danger p-1 rounded'>Deactive</span>";
          })
          ->rawColumns(['action','status'])->make(true);
        }
        return view('backend.multi_notes.multi_notes');
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
            'note'=>"required|max:200|min:1",
            'status'=>"required|max:1|min:1",
        ]);
        if($validator->passes()){
            $note=new MultiNote;
            $note->note=$request->note;
            $note->status=$request->status;
            $note->author_id=auth()->user()->id;
            $note->save();
            if ($note) {
                return response()->json(['message'=>'Note Added Success']);
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
        return response()->json(MultiNote::find($id));
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
            'note'=>"required|max:200|min:1",
            'status'=>"required|max:1|min:1",
        ]);
        if($validator->passes()){
            $note=MultiNote::find($id);
            $note->note=$request->note;
            $note->status=$request->status;
            $note->author_id=auth()->user()->id;
            $note->save();
            if ($note) {
                return response()->json(['message'=>'Note Updated Success']);
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

    public function getMultiNote(Request $request){
        $donors= MultiNote::where('note','like','%'.$request->searchTerm.'%')->where('status',1)->take(20)->get();
        foreach ($donors as $value){
             $set_data[]=['id'=>$value->id,'text'=>$value->note];
         }
         return $set_data;
     }
}
