<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PInvoice extends Model
{
    use HasFactory;
    protected $fillable=['supplier_id','date','chalan_no','total_item','transport','total_payable','total','payment_id','action_id','user_id','note'];
    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }
    public function purchase()
    {
        return $this->hasMany(Purchase::class,'invoice_id','id')->with('product');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
