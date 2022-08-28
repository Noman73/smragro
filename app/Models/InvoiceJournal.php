<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceJournal extends Model
{
    use HasFactory;
    public function ledger()
    {
        return $this->belongsTo(AccountLedger::class,'ledger_id','id');
    }
}
