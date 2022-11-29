<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DiscountVatTransportTypeChangeInInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('discount',20,2)->default(0.00)->change();
            $table->decimal('vat',20,2)->default(0.00)->change();
            $table->decimal('transport',20,2)->default(0.00)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('discount',20,2)->default(0)->change();
            $table->decimal('vat',20,2)->default(0)->change();
            $table->decimal('transport',20,2)->default(0)->change();
        });
    }
}
