<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountLedger extends Model
{
    use HasFactory;
    public function group(){
        return $this->belongsTo(AccountGroup::class,'group_id','id');
    }
}
