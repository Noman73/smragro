<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Invoice;
use App\Models\PInvoice;
use App\Models\AccountLedger;
use App\Models\Voucer;
use DB;
use App\Models\User;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_customer=Customer::where('type',0)->count();
        $total_supplier=Supplier::count();
        return view('backend.dashboard.dashboard',compact('total_customer','total_supplier'));
    }

    public function dashboard(Request $request)
    {
        // return $request->all();
        $customer_ledger=AccountLedger::where('name','Customer')->first()->id;
        $cond_customer_ledger=AccountLedger::where('name','Condition Customer')->first()->id;
        $supplier_ledger=AccountLedger::where('name','Supplier')->first()->id;
        $cash_ledger=AccountLedger::where('name','Cash')->first()->id;
        $from_date=date('d-m-Y 00:00:00',strtotime($request->from_date));
        $to_date=date('d-m-Y 23:59:59',strtotime($request->to_date));
        $total_customer=Customer::where('type',1)->where('created_at','>=',strval(strtotime($to_date)))->count();
        $total_supplier=Supplier::where('created_at','>=',strval(strtotime($to_date)))->count();
        $total_sale_amount=Invoice::where('sale_type',0)->orWhere('sale_type',1)->orWhere('sale_type',2)->sum('total_payable');
        $total_buy_amount=PInvoice::sum('total_payable');
        $customer_balance=Voucer::selectRaw('ifnull(sum(debit)-sum(credit),0) total')->where('date','<=',strtotime($to_date))->where('ledger_id',$customer_ledger)->first();
        $cond_customer_balance=Voucer::selectRaw('ifnull(sum(debit)-sum(credit),0) total')->where('date','<=',strtotime($to_date))->where('ledger_id',$cond_customer_ledger)->first();
        $customer_balance=$customer_balance->total+$cond_customer_balance->total;
        $supplier_balance=Voucer::selectRaw('ifnull(sum(credit)-sum(debit),0) total')->where('date','<=',strtotime($to_date))->where('ledger_id',$supplier_ledger)->first();
        $top_product=DB::select("
            select products.name,products.product_code,sum(sales.deb_qantity-sales.cred_qantity) qantity from products 
            left join sales on products.id=sales.product_id 
            group by products.id order by sum(sales.deb_qantity-sales.cred_qantity) desc limit 5
        ");
        $bank_ledger=AccountLedger::where('name','Bank')->first()->id;
        $bank_data=DB::select("
        SELECT banks.name,ifnull(banks.code,'') code,sum(ifnull(voucers.debit,0)-ifnull(voucers.credit,0)) balance from banks
        left join voucers on voucers.ledger_id=:bank_ledger and voucers.subledger_id=banks.id
        
        group by banks.id,voucers.subledger_id
        ",['bank_ledger'=>$bank_ledger]);
        $cash=Voucer::where('ledger_id',$cash_ledger)->selectRaw('ifnull(sum(debit)-sum(credit),0) total')->first();
        $cash_ledger_info=AccountLedger::where('name','Cash')->first();
        $bank=Voucer::where('ledger_id',$bank_ledger)->selectRaw('ifnull(sum(debit)-sum(credit),0) total')->first();
        $total_balance=number_format(floatval($cash->total)+floatval($bank->total),2,".","");
        return response()->json(['total_customer'=>$total_customer,'total_supplier'=>$total_supplier,'total_sale_amount'=>$total_sale_amount,'top_product'=>$top_product,'total_buy_amount'=>$total_buy_amount,'customer_balance'=>$customer_balance,'supplier_balance'=>$supplier_balance->total,'bank'=>$bank_data,'current_balance'=>$total_balance,'total_bank'=>$bank->total,'total_cash'=>$cash,'cash_ledger_info'=>$cash_ledger_info]);
    }
}
