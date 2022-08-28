<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Customer;
use App\Models\AccountLedger;
use DataTables;
use DB;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $get=Customer::where('type',1)->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('customer.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('customer.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          ->rawColumns(['action'])->make(true);
        }
        return view('backend.customer.customer');
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
            'company_name'=>"nullable|max:200|min:1",
            'name'=>"required|max:200|min:1",
            'email'=>"nullable|email|max:200|min:1",
            'phone'=>"required|max:200|min:1",
            'adress'=>"nullable|max:200|min:1",
            'opening_balance'=>"nullable|max:200|min:1",
            'balance_type'=>"required|max:200|min:1",
            'nid'=>"nullable|max:200|min:1",
            'birth_date'=>"nullable|max:200|min:1",
            'image'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if($validator->passes()){
            $ledger_code=AccountLedger::where('name','Customer')->first();
            $countCustomer=Customer::where('type',1)->count();
            $customer=new Customer;
            $customer->company_name=$request->company_name;
            $customer->name=$request->name;
            $customer->email=$request->email;
            $customer->phone=$request->phone;
            $customer->code=$ledger_code->code.'-'.(($countCustomer ==null? 0 : $countCustomer)+1);
            $customer->adress=$request->adress;
            $customer->nid=$request->nid;
            $customer->birth_date=$request->birth_date;
            $customer->type=1;
            $customer->author_id=auth()->user()->id;
            if ($request->hasFile('image')) {
                $ext = $request->image->getClientOriginalExtension();
                $name =auth()->user()->id  .'_'. time() . '.' . $ext;
                $request->image->storeAs('public/customer', $name);
                $customer->image = $name;
            }
            $customer->save();
            if ($customer) {
                return response()->json(['message'=>'Customer Added Success']);
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
        return response()->json(Customer::find($id));
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
        // return response()->json($request->all());
        // 
        $validator=Validator::make($request->all(),[
            'company_name'=>"nullable|max:200|min:1",
            'name'=>"required|max:200|min:1",
            'email'=>"nullable|email|max:200|min:1",
            'phone'=>"required|max:200|min:1",
            'adress'=>"nullable|max:200|min:1",
            'opening_balance'=>"nullable|max:200|min:1",
            'balance_type'=>"required|max:200|min:1",
            'nid'=>"nullable|max:200|min:1",
            'birth_date'=>"nullable|max:200|min:1",
            'image'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048|',
        ]);
        if($validator->passes()){
            $customer=Customer::find($id);
            $customer->company_name=$request->company_name;
            $customer->name=$request->name;
            $customer->email=$request->email;
            $customer->phone=$request->phone;
            $customer->adress=$request->adress;
            if($request->balance_type==0){
                $balance=-abs($request->opening_balance);
            }else{
                $balance=$request->opening_balance;
            }
            $customer->opening_balance=$balance;
            $customer->nid=$request->nid;
            $customer->birth_date=$request->birth_date;
            $customer->author_id=auth()->user()->id;
            if ($request->hasFile('image')) {
                if($customer->image!=null){
                    unlink(storage_path('app/public/customer/'.$customer->image));
                }
                $ext = $request->image->getClientOriginalExtension();
                $name = auth()->user()->id  .'_'. time() . '.' . $ext;
                $request->image->storeAs('public/customer', $name);
                $customer->image = $name;
            }
            $customer->save();
            if ($customer) {
                return response()->json(['message'=>'Customer Updated Success']);
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
    public function getCustomer(Request $request){
        $customer= Customer::where('name','like','%'.$request->searchTerm.'%')->orWhere('code','like','%'.$request->searchTerm.'%')->where('type',1)->take(15)->get();
        foreach ($customer as $value){
             $set_data[]=['id'=>$value->id,'text'=>($value->code!=null? $value->code.'-': '').$value->name.'('.$value->phone.')'];
         }
         return $set_data;
     }
     public function getBalance($id)
     {
         return $total=DB::select("select sum(voucers.debit-voucers.credit) total from voucers left join account_ledgers on account_ledgers.id=voucers.ledger_id where (transaction_name='customers' or account_ledgers.name='Customer') and (subledger_id=:id or person_id=:person_id)",['id'=>$id,'person_id'=>$id]);
     }
     public function checkCustomer(Request $request){
        //  return $request->all();
        $check=Customer::where('phone',$request->mobile)->first();
        // return $check;
        if($check!=null){
            return response()->json(['exist'=>$check]);
        }else{
            return response()->json(['404'=>'not found']);
        }
     }
}