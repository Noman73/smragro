<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PInvoice;
use Validator;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Voucer;
use App\Models\AccountLedger;
use Auth;
class PurchaseController extends Controller
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
        return view('backend.purchase.purchase');
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

        return response()->json($r->all());
        $data=$r->all();
        $data['product'] = explode(',', $r->product);
        $data['qantity'] = explode(',', $r->qantity);
        $data['price'] = explode(',', $r->price);
        // return $data['product'];
        if (isset($r->supplier) and $r->supplier=='null') {
            $data['supplier'] = null;
        } else {
            $data['supplier'] = $r->supplier;
        }
        if (isset($r->payment_method) and $r->payment_method=='null') {
            $data['payment_method'] = null;
        } else {
            $data['payment_method'] = $r->payment_method;
        }
        if($data['purchase_type']==1){
            $supplier_cond="required";
        }else{
            $supplier_cond="nullable";
        }
        $validator = Validator::make($data, [
            'product' => 'required|array',
            'product.*' => 'required|distinct|regex:/^([0-9]+)$/',
            'qantity' => 'required|array',
            'qantity.*' => 'required|regex:/^([0-9.]+)$/',
            'price' => 'required|array',
            'price.*' => 'required|regex:/^([0-9.]+)$/',
            'transport' => 'nullable|regex:/^([0-9]+)$/',
            'purchase_type' => 'required|regex:/^([0-2]+)$/',
            'supplier' => $supplier_cond.'|regex:/^([0-9]+)$/',
            'date' => 'required|max:10|date_format:d-m-Y',
            'issue_date' => 'nullable|max:10|date_format:d-m-Y',
            'total_payable' => 'required|max:10|regex:/^([0-9.]+)$/',
            'total_item' => 'required|max:10|regex:/^([0-9.]+)$/',
            'vat' => 'nullable|max:15|regex:/^([0-9.]+)$/',
            'total' => 'required|max:15|regex:/^([0-9.]+)$/',
            'payment_method' => 'nullable|max:10|regex:/^([0-9]+)$/',
            'transaction' => 'nullable|max:50|regex:/^([a-zA-Z0-9]+)$/',
            'ammount' => 'nullable|max:18|regex:/^([0-9.]+)$/',
            'note' => 'nullable|max:500',
            'staff_note' => 'nullable|max:500',
            'chalan_no' => 'nullable|max:500',
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
            
            $invoice = new PInvoice;
            $invoice->dates = strtotime(strval($data['date']));
            $invoice->supplier_id = $data['supplier'];
            $invoice->chalan_no = $data['chalan_no'];
            $invoice->total_item = $data['total_item'];
            $invoice->vat = $data['vat'];
            $invoice->transport = $data['transport'];
            $invoice->total_payable = $data['total_payable'];
            $invoice->total = $data['total'];
            $invoice->purchase_type = $data['purchase_type'];
            $invoice->action_id = $data['action'];
            $invoice->note = $data['note'];
            $invoice->staff_note = $data['staff_note'];
            $invoice->user_id = auth()->user()->id;
            $invoice->save();
            $inv_id = $invoice->id;
            $user_id = $invoice->user_id;
            if ($invoice = true) {
                $length = intval($data['total_item']) - 1;
                for ($i = 0; $i <= $length; $i++) {
                    $stmt = new Purchase();
                    $stmt->invoice_id = $inv_id;
                    $stmt->dates = strtotime(strval($data['date']));
                    $stmt->supplier_id = $data['supplier'];
                    $stmt->product_id = $data['product'][$i];
                    if ($data['action'] != 0) {
                        $stmt->deb_qantity = $data['qantity'][$i];
                    } else {
                        $stmt->cred_qantity = $data['qantity'][$i];
                    }
                    $stmt->price = $data['price'][$i];
                    $stmt->user_id = $user_id;
                    $stmt->action_id = $data['action'];
                    $stmt->purchase_type = $data['purchase_type'];
                    $stmt->save();
                }
                if ($stmt = true) {
                    
                    $purchase_ledger=AccountLedger::where('name','Purchase')->first();
                    $cash_ledger=AccountLedger::where('name','Cash')->first();
                    // when purchase by cash
                    if($data['purchase_type']==0){
                        $voucer = new Voucer();
                        $voucer->date= strtotime(strval($data['date']));
                        $voucer->transaction_name = 'suppliers';
                        $voucer->person_id = $data['supplier'];
                        $voucer->debit = 0;
                        $voucer->credit = $data['ammount'];
                        $voucer->invoice_id = $inv_id;
                        $voucer->author_id = auth()->user()->id;
                        $voucer->save();
                        $voucer = new Voucer();
                        $voucer->date= strtotime(strval($data['date']));
                        $voucer->ledger_id = $purchase_ledger->id;
                        $voucer->debit = $total_payable;
                        $voucer->credit = 0;
                        $voucer->invoice_id = $inv_id;
                        $voucer->author_id = auth()->user()->id;
                        $voucer->save();
                        return ['message' => 'Invoice Added Success', 'id' => $inv_id];
                    }else{
                            if ($data['ammount'] != null or $data['ammount']!=0) {
                                if(floatval($data['ammount'])<floatval($total_payable)){
                                    $due_ammount=$total_payable-floatval($data['ammount']);
                                    // purchase dabit
                                    $voucer = new Voucer();
                                    $voucer->date= strtotime(strval($data['date']));
                                    $voucer->ledger_id = $purchase_ledger->id;
                                    $voucer->debit = $total_payable;
                                    $voucer->credit=0;
                                    $voucer->invoice_id = $inv_id;
                                    $voucer->author_id = auth()->user()->id;
                                    $voucer->save();
                                    // supplier credit 
                                    $voucer = new Voucer();
                                    $voucer->date= strtotime(strval($data['date']));
                                    $voucer->transaction_name = 'customers';
                                    $voucer->person_id = $data['customer'];
                                    $voucer->debit = 0;
                                    $voucer->credit = $total_payable;
                                    $voucer->invoice_id = $inv_id;
                                    $voucer->author_id = auth()->user()->id;
                                    $voucer->save();
                                    // cash/bank credit
                                    $voucer = new Voucer();
                                    $voucer->account_id = $data['payment_method'];
                                    $voucer->date= strtotime(strval($data['date']));
                                    $voucer->transaction_name = 'customers';
                                    $voucer->person_id = $data['customer'];
                                    if($data['payment_method_type']==0){
                                        $voucer->ledger_id=$cash_ledger->id;
                                    }
                                    $voucer->debit =0;
                                    $voucer->credit = $data['ammount'];
                                    $voucer->cheque_no = $data['cheque_no'];
                                    $voucer->cheque_issue_date = strtotime(strval($data['cheque_issue_date']));
                                    $voucer->invoice_id = $inv_id;
                                    $voucer->author_id = auth()->user()->id;
                                    $voucer->save();
                                    // supplier dabit
                                    $voucer = new Voucer();
                                    $voucer->account_id = $data['payment_method'];
                                    $voucer->date= strtotime(strval($data['date']));
                                    $voucer->transaction_name = 'customers';
                                    $voucer->person_id = $data['customer'];
                                    $voucer->debit =$data['ammount'];
                                    $voucer->credit = 0;
                                    $voucer->cheque_no = $data['cheque_no'];
                                    $voucer->cheque_issue_date = strtotime(strval($data['cheque_issue_date']));
                                    $voucer->invoice_id = $inv_id;
                                    $voucer->author_id = auth()->user()->id;
                                    $voucer->save();
                                    return ['message' => 'Invoice Added Success', 'id' => $inv_id];
                                }else{
                                    $voucer = new Voucer();
                                    $voucer->date= strtotime(strval($data['date']));
                                    $voucer->ledger_id = $purchase_ledger->id;
                                    $voucer->debit = $total_payable;
                                    $voucer->credit =0;
                                    $voucer->invoice_id = $inv_id;
                                    $voucer->author_id = auth()->user()->id;
                                    $voucer->save();
                                    // cash/bank credit
                                    $voucer = new Voucer();
                                    $voucer->account_id = $data['payment_method'];
                                    $voucer->date= strtotime(strval($data['date']));
                                    $voucer->transaction_name = 'customers';
                                    $voucer->person_id = $data['customer'];
                                    if($data['payment_method_type']==0){
                                        $voucer->ledger_id=$cash_ledger->id;
                                    }
                                    $voucer->debit =0;
                                    $voucer->credit = $data['ammount'];
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
