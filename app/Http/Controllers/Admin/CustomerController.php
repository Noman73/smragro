<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Customer;
use App\Models\AccountLedger;
use App\Models\Voucer;
use DataTables;
use DB;
use App\Http\Traits\BalanceTrait;
class CustomerController extends Controller
{


    use BalanceTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Customer View',['only'=>'index']);
        $this->middleware('permission:Customer Create',['only'=>'store']);
        $this->middleware('permission:Customer Edit',['only'=>'edit']);
        $this->middleware('permission:Customer Edit',['only'=>'update']);
        $this->middleware('permission:Customer Delete',['only'=>'destroy']);
    }
    public function index()
    {
        if(request()->ajax()){
            $get=Customer::where('type',1)->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
              $button.='<a data-url="'.route('customer.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a href="'.route('customer.show',$get->id).'" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1 ml-1"><i class="fas fa-print"></i></a>
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
            'market'=>"required|max:15|min:1",
            'phone2'=>"nullable|max:200|min:1",
            'bank_name'=>"nullable|max:200|min:1",
            'bank_account_no'=>"nullable|max:200|min:1",
            'adress'=>"nullable|max:200|min:1",
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
            $customer->market_id=$request->market;
            $customer->email=$request->email;
            $customer->phone=$request->phone;
            $customer->phone2=$request->phone2;
            $customer->bank_name=$request->bank_name;
            $customer->bank_account_no=$request->bank_account_no;
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
        $customer_ledger=AccountLedger::where('name','Customer')->first()->id;
        $customer=Customer::with('credit_limit','author')->find($id);
        $current_balance=$this->customerBalance($id);
        $customer->current_balance=$current_balance;
        $customer->last_trx=Voucer::where('ledger_id',$customer_ledger)->where('subledger_id',$id)->orderBy('id','desc')->first()->date;
        return view('backend.customer.print',compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json(Customer::with('market')->find($id));
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
        $validator=Validator::make($request->all(),[
            'company_name'=>"nullable|max:200|min:1",
            'name'=>"required|max:200|min:1",
            'market'=>"required|max:15|min:1",
            'email'=>"nullable|email|max:200|min:1",
            'phone'=>"required|max:200|min:1",
            'phone2'=>"nullable|max:200|min:1",
            'bank_name'=>"nullable|max:200|min:1",
            'bank_account_no'=>"nullable|max:200|min:1",
            'adress'=>"nullable|max:200|min:1",
            'nid'=>"nullable|max:200|min:1",
            'birth_date'=>"nullable|max:200|min:1",
            'image'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048|',
        ]);
        if($validator->passes()){
            $customer=Customer::find($id);
            $customer->company_name=$request->company_name;
            $customer->name=$request->name;
            $customer->market_id=$request->market;
            $customer->email=$request->email;
            $customer->phone=$request->phone;
            $customer->phone2=$request->phone2;
            $customer->bank_name=$request->bank_name;
            $customer->bank_account_no=$request->bank_account_no;
            $customer->adress=$request->adress;
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
        // return $request->all();
        $searchTerm=$request->searchTerm;
        $market=$request->market;
        $customer= Customer::where('type',1)->where(function($query) use ($searchTerm){
            $query->where('name','like','%'.$searchTerm.'%')
                    ->orWhere('code','like','%'.$searchTerm.'%');
        })->where(function($query) use ($market){
            if($market!=null){
                $query->orWhere('market_id',$market);
            }
        })
        ->take(15)->get();
        foreach ($customer as $value){
             $set_data[]=['id'=>$value->id,'text'=>$value->name.'('.$value->phone.')'];
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