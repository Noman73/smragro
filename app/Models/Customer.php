<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    public function author()
    {
        return $this->belongsTo(User::class,'author_id','id');
    }
    public function credit_limit()
    {
        return $this->belongsTo(CreditSetup::class,'id','customer_id');
    }
    
    public function market()
    {
        return $this->belongsTo(Market::class,'market_id','id');
    }
}
