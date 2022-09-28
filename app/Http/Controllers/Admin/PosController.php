<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PosController extends Controller
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
        return view('backend.pos.pos');
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
            'transport' => 'nullable|regex:/^([0-9]+)$/',
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
            'warehouse' => 'required|max:15',
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
            $invoice->store_id = $data['warehouse'];
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
            $invoice->action_id = $data['action'];
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
                    $stmt->store_id = $data['warehouse'];
                    $stmt->dates = strtotime(strval($data['date']));
                    $stmt->customer_id = $customer_id;
                    $stmt->product_id = $data['product'][$i];
                    $stmt->deb_qantity = $data['qantity'][$i];
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
                    $customer_ledger=AccountLedger::where('name','Customer')->first();
                    $sales_amt_without_discount=$total-($data['discount_type']==0? floatval($data['discount']) : ($total*floatval($data['discount']))/100);
                    // vat journal
                    if($data['vat']!=null and $data['vat']!=0){
                        $vat_ledger=AccountLedger::where('name','Vat')->first();
                        // vat will be only credit for vat ledger
                        $vat_journal=new Voucer();
                        $vat_journal->date= strtotime(strval($data['date']));
                        $vat_journal->transaction_name ="Sale Invoice";
                        $vat_journal->ledger_id = $vat_ledger->id;
                        $vat_journal->debit = 0;
                        $vat_journal->credit =(floatval($data['vat'])*$total)/100;
                        $vat_journal->invoice_id = $inv_id;
                        $vat_journal->author_id = auth()->user()->id;
                        $vat_journal->save();
                    }
                    if($data['transport']!=null and $data['transport']!=0){
                        $vat_ledger=AccountLedger::where('name','Transport Income')->first();
                        // vat will be only credit for vat ledger
                        $vat_journal=new Voucer();
                        $vat_journal->date= strtotime(strval($data['date']));
                        $vat_journal->transaction_name ="Sale Invoice";
                        $vat_journal->ledger_id = $vat_ledger->id;
                        $vat_journal->debit = 0;
                        $vat_journal->credit =$data['transport'];
                        $vat_journal->invoice_id = $inv_id;
                        $vat_journal->author_id = auth()->user()->id;
                        $vat_journal->save();
                    }
                    // end vat journal
                    if($data['sale_type']==0){
                        // sales credit
                        $voucer = new Voucer();
                        $voucer->date= strtotime(strval($data['date']));
                        $voucer->transaction_name="Sale Invoice";
                        $voucer->ledger_id = $cash_ledger->id;
                        $voucer->person_id = $customer_id;
                        $voucer->debit = $sales_amt_without_discount;
                        $voucer->credit = 0;
                        $voucer->invoice_id = $inv_id;
                        $voucer->author_id = auth()->user()->id;
                        $voucer->save();
                        // cash dabit
                        $voucer = new Voucer();
                        $voucer->date= strtotime(strval($data['date']));
                        $voucer->transaction_name ="Sale Invoice";
                        $voucer->ledger_id = $sales_ledger->id;
                        $voucer->debit = 0;
                        $voucer->credit = $total_payable;
                        $voucer->invoice_id = $inv_id;
                        $voucer->author_id = auth()->user()->id;
                        $voucer->save();
                        return ['message' => 'Invoice Added Success', 'id' => $inv_id];
                    }elseif($data['sale_type']==1){
                        // sales credit
                        $voucer = new Voucer();
                        $voucer->date= strtotime(strval($data['date']));
                        $voucer->transaction_name ="Sale Invoice";
                        $voucer->ledger_id = $sales_ledger->id;
                        $voucer->credit = $sales_amt_without_discount;
                        $voucer->invoice_id = $inv_id;
                        $voucer->author_id = auth()->user()->id;
                        $voucer->save();
                        // customer debit
                        $voucer = new Voucer();
                        $voucer->date= strtotime(strval($data['date']));
                        $voucer->transaction_name="Sale Invoice";
                        $voucer->ledger_id = $customer_ledger->id;
                        $voucer->subledger_id = $customer_id;
                        $voucer->debit = $total_payable;
                        $voucer->invoice_id = $inv_id;
                        $voucer->author_id = auth()->user()->id;
                        $voucer->save();
                        if($voucer){
                            return ['message' => 'Invoice Added Success', 'id' => $inv_id];
                        }
                    }else{
                            if ($data['ammount'] != null or $data['ammount']!=0) {
                                if(floatval($data['ammount'])<floatval($total_payable)){
                                    $due_ammount=$total_payable-floatval($data['ammount']);
                                    // sales credit
                                    $voucer = new Voucer();
                                    $voucer->date= strtotime(strval($data['date']));
                                    $voucer->transaction_name="Sale Invoice";
                                    $voucer->ledger_id = $sales_ledger->id;
                                    $voucer->debit = 0;
                                    $voucer->credit =$sales_amt_without_discount;
                                    $voucer->invoice_id = $inv_id;
                                    $voucer->author_id = auth()->user()->id;
                                    $voucer->save();
                                    // customer dabit 
                                    $voucer = new Voucer();
                                    $voucer->date= strtotime(strval($data['date']));
                                    $voucer->transaction_name = 'Sale Invoice';
                                    $voucer->ledger_id=$customer_ledger->id;
                                    $voucer->subledger_id=$customer_id;
                                    $voucer->debit = $total_payable;
                                    $voucer->credit = 0;
                                    $voucer->invoice_id = $inv_id;
                                    $voucer->author_id = auth()->user()->id;
                                    $voucer->save();
                                    // cash/bank debit
                                    $voucer = new Voucer();
                                    $voucer->account_id = $data['payment_method'];
                                    $voucer->date= strtotime(strval($data['date']));
                                    $voucer->transaction_name="Sale Invoice";
                                    if($data['payment_method_type']==0){
                                        $voucer->ledger_id=$cash_ledger->id;
                                    }
                                    $voucer->debit =$data['ammount'];
                                    $voucer->credit = 0;
                                    $voucer->cheque_no = $data['cheque_no'];
                                    $voucer->cheque_issue_date = strtotime(strval($data['cheque_issue_date']));
                                    $voucer->invoice_id = $inv_id;
                                    $voucer->author_id = auth()->user()->id;
                                    $voucer->save();
                                    // customer credit
                                    $voucer = new Voucer();
                                    $voucer->account_id = $data['payment_method'];
                                    $voucer->date= strtotime(strval($data['date']));
                                    $voucer->transaction_name = 'Sale Invoice';
                                    $voucer->ledger_id = $customer_ledger->id;
                                    $voucer->subledger_id = $customer_id;
                                    $voucer->credit = $data['ammount'];
                                    $voucer->cheque_no = $data['cheque_no'];
                                    $voucer->cheque_issue_date = strtotime(strval($data['cheque_issue_date']));
                                    $voucer->invoice_id = $inv_id;
                                    $voucer->author_id = auth()->user()->id;
                                    $voucer->save();
                                    return ['message' => 'Invoice Added Success', 'id' => $inv_id];
                                }else{
                                    $voucer = new Voucer();
                                    $voucer->date= strtotime(strval($data['date']));
                                    $voucer->transaction_name ="Sale Invoice";
                                    $voucer->ledger_id = $sales_ledger->id;
                                    $voucer->credit =$sales_amt_without_discount;
                                    $voucer->invoice_id = $inv_id;
                                    $voucer->author_id = auth()->user()->id;
                                    $voucer->save();
                                    // cash/bank debit
                                    $voucer = new Voucer();
                                    $voucer->date= strtotime(strval($data['date']));
                                    if($data['payment_method_type']==0){
                                        $voucer->ledger_id=$cash_ledger->id;
                                    }else{
                                        $voucer->account_id = $data['payment_method'];
                                        $voucer->date= strtotime(strval($data['date']));
                                    }
                                    $voucer->transaction_name="Sale Invoice";
                                    $voucer->debit =$data['ammount'];
                                    $voucer->cheque_no = $data['cheque_no'];
                                    $voucer->cheque_issue_date = strtotime(strval($data['cheque_issue_date']));
                                    $voucer->invoice_id = $inv_id;
                                    $voucer->author_id = auth()->user()->id;
                                    $voucer->save();
                                    return ['message' => 'Invoice Added Success', 'id' => $inv_id];
                                }
                                return ['message' => 'Invoice Added Success', 'id' => $inv_id];
                            }
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
}