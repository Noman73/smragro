<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeVoucersDebitAndCreditLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voucers', function (Blueprint $table) {
            $table->decimal('debit',20,2)->change();
            $table->decimal('credit',20,2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('=voucers', function (Blueprint $table) {
            $table->decimal('debit',8,2)->change();
            $table->decimal('credit',8,2)->change();
        });
    }
}
