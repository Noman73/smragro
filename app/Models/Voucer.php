<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucer extends Model
{
    use HasFactory;
    public function sale()
    {
        return $this->hasMany(Sale::class,'invoice_id','invoice_id')->with('product');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class,'subledger_id','id');
    }

    public function ledgers()
    {
        return $this->belongsTo(AccountLedger::class,'ledgers_id','id');
    }
}
