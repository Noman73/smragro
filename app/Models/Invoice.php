<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AccountLedger;
class Invoice extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

    public function pay()
    {
        $bank_ledger=AccountLedger::where('name','Bank')->first();
        $cash_ledger=AccountLedger::where('name','Cash')->first();
        return $this->hasMany(Voucer::class,'invoice_id','id')->where('ledger_id',$bank_ledger->id)->orWhere('ledger_id',$cash_ledger->id);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class,'invoice_id','id')->with('product');
    }
    public function condition_amount()
    {
        $bank_ledger=AccountLedger::where('name','Bank')->first();
        $cash_ledger=AccountLedger::where('name','Cash')->first();
        return $this->belongsTo(Voucer::class,'id','invoice_id')->where('ledger_id',$bank_ledger->id)->orWhere('ledger_id',$cash_ledger->id)->where('transaction_name','Sale Invoice');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'author_id','id');
    }
    public function notes()
    {
        return $this->belongsTo(MultiNote::class,'note_id','id');
    }
    public function shipping_customer(){
        return $this->belongsTo(ShippingAdress::class,'shipped_adress_id','id');
    }
    public function courier()
    {
        return $this->belongsTo(ShippingCompany::class,'shipping_id','id');
    }

    public function road_chalan()
    {
        return $this->belongsTo(RoadChalan::class,'id','invoice_id');
    }
}
