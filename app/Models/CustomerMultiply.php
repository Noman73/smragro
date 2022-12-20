<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMultiply extends Model
{
    use HasFactory;
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id','id');
    }
}
