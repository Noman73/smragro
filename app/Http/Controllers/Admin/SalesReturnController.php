<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Validator;
use App\Models\Invoice;
use App\Models\Sale;
use App\Models\AccountLedger;
use App\Models\Voucer;
use App\Models\Product;
use App\Rules\CheckCreditLimit;
use App\Models\ShippingAdress;
use DataTables;
use URL;
class SalesReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Sales Return View',['only'=>'index']);
        $this->middleware('permission:Sales Return Create',['only'=>'store']);
        $this->middleware('permission:Sales Return Edit',['only'=>'edit']);
        $this->middleware('permission:Sales Return Edit',['only'=>'update']);
        $this->middleware('permission:Sales Return Delete',['only'=>'destroy']);
    }
    public function index()
    {
        return view('backend.sales_return.sales_return');
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
    public function store(Request $r)
    {
            // return $r->all();
            $data=$r->all();
            $data['product'] = explode(',', $r->product);
            $data['qantity'] = explode(',', $r->qantity);
            $data['price'] = explode(',', $r->price);
            // return $data['product'];
            if (isset($r->customer) and $r->customer=='null') {
                $data['customer'] = null;
            } else {
                $data['customer'] = $r->customer;
            }
            if (isset($r->payment_method) and $r->payment_method=='null') {
                $data['payment_method'] = null;
            } else {
                $data['payment_method'] = $r->payment_method;
            }
            if($r->courier=='null'){
                $data['courier']=null;
            }
            if($data['sale_type']==1){
                $customer_cond="required";
            }else{
                $customer_cond="nullable";
            }
            if($data['sale_type']==0){
                $sale_cond="required";
            }else{
                $sale_cond="nullable";
            }
            if($data['mobile']!=null){
                $wcustomer='required';
            }else{
                $wcustomer='nullable';
            }
            if($data['sale_type']==2){
                $wcustomer='required';
            }else{
                $wcustomer='nullable';
            }
            if($data['sale_by']>0 ){
                $courier='required';
            }else{
                $courier='nullable';
            }
            if($data['sale_by']==2){
                $shipping_adress='required';
            }else{
                $shipping_adress='nullable';
            }
            $validator = Validator::make($data, [
                'product' => 'required|array',
                'product.*' => 'required|distinct|regex:/^([0-9]+)$/',
                'qantity' => 'required|array',
                'qantity.*' => 'required|regex:/^([0-9.]+)$/',
                'price' => 'required|array',
                'price.*' => 'required|regex:/^([0-9.]+)$/',
                'transport' => 'nullable|regex:/^([0-9.]+)$/',
                'sale_type' => 'required|regex:/^([0-2]+)$/',
                'discount_type' => 'required|regex:/^([0-2]+)$/',
                'customer' => $customer_cond.'|regex:/^([0-9]+)$/',
                'date' => 'required|max:10|date_format:d-m-Y',
                'issue_date' => 'nullable|max:10|date_format:d-m-Y',
                'total_payable' => ["required","max:10","regex:/^([0-9.]+)$/",new CheckCreditLimit($data['customer'])],
                'total_item' => 'required|max:10|regex:/^([0-9.]+)$/',
                'vat' => 'nullable|max:15|regex:/^([0-9.]+)$/',
                'total' => 'required|max:15|regex:/^([0-9.]+)$/',
                'payment_method' => 'nullable|max:10|regex:/^([0-9]+)$/',
                'transaction' => 'nullable|max:50|regex:/^([a-zA-Z0-9]+)$/',
                'ammount' => $sale_cond.'|max:18|regex:/^([0-9.]+)$/',
                'note' => 'nullable|max:500',
                'staff_note' => 'nullable|max:500',
                'sale_by'=>'required|max:20|min:1',
                'courier'=>$courier.'|max:20|min:1',
                'shipping_name'=>$shipping_adress.'|max:20|min:1',
                'shipping_mobile'=>$shipping_adress.'|max:20|min:1',
                'shipping_adress'=>$shipping_adress.'|max:20|min:1',
                'condition_amount'=>$shipping_adress.'|max:20|min:1',
                // walking customer
                'mobile' => $wcustomer.'|max:15|min:11',
                'name' => $wcustomer.'|min:1|max:200',
                'mobile' => $wcustomer.'|max:11',
            ]);
    
            if ($validator->passes()) {
                $length = intval($data['total_item']) - 1;
                $total_payable=0;
                $total=0;
                for ($i = 0; $i <= $length; $i++) {
                    $total+=floatval($data['price'][$i])*floatval($data['qantity'][$i]);
                }
                $total_payable+=$total+($data['vat']==null ? 0: ($total*floatval($data['vat']))/100);
                $total_payable+=($data['transport']==null ? 0: $data['transport']);
                if($data['discount_type']==1){
                    $total_payable-=($data['discount']==null ? 0: ($total*$data['discount'])/100);
                }else{
                    $total_payable-=($data['discount']==null ? 0: $data['discount']);
                }
                if($data['sale_by']==2){
                    $shipping_adress=new ShippingAdress;
                    $shipping_adress->name=$data['shipping_name'];
                    $shipping_adress->phone=$data['shipping_mobile'];
                    $shipping_adress->adress=$data['shipping_adress'];
                    $shipping_adress->author_id= auth()->user()->id;
                    $shipping_adress->save();
                    $adress_id=$shipping_adress->id;
                }else{
                    $adress_id=null;
                }
                // search customer
                if($data['mobile']!=null){
                    $existance_customer=Customer::where('phone',$data['mobile'])->count();
                    if($existance_customer<=0){
                        $customer=new Customer;
                        $customer->name=$data['name'];
                        $customer->phone=$data['mobile'];
                        $customer->adress=$data['adress'];
                        $customer->type=$data['sale_type'];
                        $customer->author_id=auth()->user()->id;
                        $customer->save();
                        $customer_id=$customer->id;
                    }else{
                        $customer_id=Customer::where('phone',$data['mobile'])->first()->id;
                    }
                }else{
                    $customer_id=$data['customer'];
                }
                $invoice = new Invoice;
                $invoice->dates = strtotime(strval($data['date']));
                $invoice->hand_bill = $data['hand_bill'];
                $invoice->customer_id = $customer_id;
                $invoice->shipping_id = $data['courier'];
                $invoice->shipped_adress_id = $adress_id;
                $invoice->total_item = $data['total_item'];
                $invoice->vat = $data['vat'];
                $invoice->transport = $data['transport'];
                $invoice->total_payable = $total_payable;
                $invoice->total = $total;
                $invoice->sale_type = $data['sale_type'];
                $invoice->sale_by = $data['sale_by'];
                $invoice->discount_type = $data['discount_type'];
                $invoice->discount = $data['discount'];
                $invoice->action_id = 1;
                $invoice->note_id = $data['note'];
                $invoice->staff_note = $data['staff_note'];
                $invoice->cond_amount = $data['condition_amount'];
                $invoice->author_id = auth()->user()->id;
                $invoice->save();
                $inv_id = $invoice->id;
                $user_id = $invoice->author_id;
                if ($invoice = true) {
                    for ($i = 0; $i <= $length; $i++) {
                        $product=Product::find($data['product'][$i]);
                        if($product->product_type==4){
                            for ($x=0; $x < floatval($data['qantity'][$i]); $x++) {
                                $this->comboIteration($product->combobox,$product->comboqty,$data['date'],$inv_id);
                            }
                        }
                        $stmt = new Sale();
                        $stmt->invoice_id = $inv_id;
                        $stmt->dates = strtotime(strval($data['date']));
                        $stmt->customer_id = $customer_id;
                        $stmt->product_id = $data['product'][$i];
                        $stmt->cred_qantity = $data['qantity'][$i];
                        $stmt->price = $data['price'][$i];
                        $stmt->author_id = $user_id;
                        $stmt->action_id = $data['action'];
                        $stmt->sale_type = $data['sale_type'];
                        $stmt->save();
                    }
                    if ($stmt = true) {
                        // customized code 
                        $sales_ledger=AccountLedger::where('name','Sales')->first();
                        $cash_ledger=AccountLedger::where('name','Cash')->first();
                        $bank_ledger=AccountLedger::where('name','Bank')->first();
                        $customer_ledger=AccountLedger::where('name','Customer')->first();
                        $cond_customer_ledger=AccountLedger::where('name','Condition Customer')->first();
                        $sales_amt_without_discount=$total-($data['discount_type']==0? floatval($data['discount']) : ($total*floatval($data['discount']))/100);
                        // vat journal
                        if($data['vat']!=null and $data['vat']!=0){
                            $vat_ledger=AccountLedger::where('name','Vat')->first();
                            // vat will be only credit for vat ledger
                            $vat_journal=new Voucer();
                            $vat_journal->date= strtotime(strval($data['date']));
                            $vat_journal->transaction_name ="Sale Return Invoice";
                            $vat_journal->ledger_id = $vat_ledger->id;
                            $vat_journal->credit = 0;
                            $vat_journal->debit =(floatval($data['vat'])*$total)/100;
                            $vat_journal->invoice_id = $inv_id;
                            $vat_journal->author_id = auth()->user()->id;
                            $vat_journal->comment=$data['staff_note'];
                            $vat_journal->save();
                        }
                        if($data['transport']!=null and $data['transport']!=0){
                            $vat_ledger=AccountLedger::where('name','Transport Income')->first();
                            // vat will be only credit for vat ledger
                            $vat_journal=new Voucer();
                            $vat_journal->date= strtotime(strval($data['date']));
                            $vat_journal->transaction_name ="Sale Return Invoice";
                            $vat_journal->ledger_id = $vat_ledger->id;
                            $vat_journal->credit = 0;
                            $vat_journal->debit =$data['transport'];
                            $vat_journal->invoice_id = $inv_id;
                            $vat_journal->author_id = auth()->user()->id;
                            $vat_journal->comment=$data['staff_note'];
                            $vat_journal->save();
                        }
                        // end vat journal
                        if($data['sale_type']==0){
                            // Cash ledger
                            $voucer = new Voucer();
                            $voucer->date= strtotime(strval($data['date']));
                            $voucer->transaction_name="Sale Return Invoice";
                            $voucer->ledger_id = $cash_ledger->id;
                            $voucer->person_id = $customer_id;
                            $voucer->credit = $total_payable;
                            $voucer->debit = 0;
                            $voucer->invoice_id = $inv_id;
                            $voucer->author_id = auth()->user()->id;
                            $voucer->comment=$data['staff_note'];
                            $voucer->save();
                            // sales ledger
                            $voucer = new Voucer();
                            $voucer->date= strtotime(strval($data['date']));
                            $voucer->transaction_name ="Sale Return Invoice";
                            $voucer->ledger_id = $sales_ledger->id;
                            $voucer->credit = 0;
                            $voucer->debit = $sales_amt_without_discount;
                            $voucer->invoice_id = $inv_id;
                            $voucer->author_id = auth()->user()->id;
                            $voucer->comment=$data['staff_note'];
                            $voucer->save();
                            return ['message' => 'Invoice Added Success', 'id' => $inv_id];
                        }else{
                            $due_ammount=$total_payable-floatval($data['ammount']);
                            // sales credit
                            $voucer = new Voucer();
                            $voucer->date= strtotime(strval($data['date']));
                            $voucer->transaction_name="Sale Return Invoice";
                            $voucer->ledger_id = $sales_ledger->id;
                            $voucer->credit = 0;
                            $voucer->debit =$sales_amt_without_discount;
                            $voucer->invoice_id = $inv_id;
                            $voucer->author_id = auth()->user()->id;
                            $voucer->comment=$data['staff_note'];
                            $voucer->save();
                            // customer dabit 
                            $voucer = new Voucer();
                            $voucer->date= strtotime(strval($data['date']));
                            $voucer->transaction_name = 'Sale Return Invoice';
                            $voucer->ledger_id=($data['sale_type']==2 ? $cond_customer_ledger->id : $customer_ledger->id);
                            $voucer->subledger_id=$customer_id;
                            $voucer->credit = $total_payable;
                            $voucer->debit = 0;
                            $voucer->invoice_id = $inv_id;
                            $voucer->author_id = auth()->user()->id;
                            $voucer->comment=$data['staff_note'];
                            $voucer->save();
                            // cash/bank debit
                            $voucer = new Voucer();
                            $voucer->account_id = $data['payment_method'];
                            $voucer->date= strtotime(strval($data['date']));
                            $voucer->transaction_name="Sale Invoice";
                            if($data['payment_method_type']==0){
                                $voucer->ledger_id=$cash_ledger->id;
                            }else{
                                $voucer->ledger_id=$bank_ledger->id;
                                $voucer->subledger_id=$data['payment_method'];
                            }
                            $voucer->credit =($data['ammount']==null ? 0 : $data['ammount']);
                            $voucer->debit = 0;
                            $voucer->cheque_no = $data['cheque_no'];
                            $voucer->cheque_issue_date = strtotime(strval($data['cheque_issue_date']));
                            $voucer->invoice_id = $inv_id;
                            $voucer->author_id = auth()->user()->id;
                            $voucer->comment=$data['staff_note'];
                            $voucer->save();
                            // customer credit
                            $voucer = new Voucer();
                            $voucer->account_id = $data['payment_method'];
                            $voucer->date= strtotime(strval($data['date']));
                            $voucer->transaction_name = 'Sale Return Invoice';
                            $voucer->ledger_id = ($data['sale_type']==2 ? $cond_customer_ledger->id : $customer_ledger->id);
                            $voucer->subledger_id = $customer_id;
                            $voucer->debit = ($data['ammount']==null ? 0 : $data['ammount']);
                            $voucer->cheque_no = $data['cheque_no'];
                            $voucer->cheque_issue_date = strtotime(strval($data['cheque_issue_date']));
                            $voucer->invoice_id = $inv_id;
                            $voucer->author_id = auth()->user()->id;
                            $voucer->comment=$data['staff_note'];
                            $voucer->save();
                            return ['message' => 'Invoice Added Success', 'id' => $inv_id];
                        }
                    }
                    
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
    public function comboIteration($products,$qantities,$date,$invoice_id)
    {
        $product=explode(',', $products);
        $qantity=explode(',', $qantities);
        $i=0;
        foreach($product as $product_id){
            $stmt = new Sale();
            $stmt->invoice_id = $invoice_id;
            $stmt->dates = strtotime(strval($date));
            $stmt->product_id = $product_id;
            $stmt->deb_qantity =0;
            $stmt->cred_qantity=$qantity[$i];
            $stmt->price = 0;
            $stmt->author_id = auth()->user()->id;
            $stmt->action_id = 1;
            $stmt->sale_type = 0;
            $stmt->is_combo=1;
            $stmt->save();
            $i=$i+1;
        }          
    }
    public function returnList()
    {
        // return $get=Invoice::with('customer')->get();
        if(request()->ajax()){
            $get=Invoice::with('customer','user')->where('action_id',1)->orderBy('dates','desc')->get();
            return DataTables::of($get)
                ->addIndexColumn()
                ->addColumn('action',function($get){
                $button  ='<div class="d-flex justify-content-center">';
                $button.='<a href="'.URL::to('admin/view-pages/sales-return-invoice/'.$get->id).'" class="btn btn-warning shadow btn-xs sharp me-1"><i class="fas fa-eye"></i></a>
                <a href="'.route('invoice.edit',$get->id).'" class="btn btn-primary shadow btn-xs sharp ml-1 editRow"><i class="fas fa-pencil-alt"></i></a>
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
        return view('backend.sales_return.invoice-list');
    }
}
