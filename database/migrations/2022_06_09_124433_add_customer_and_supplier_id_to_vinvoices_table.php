<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerAndSupplierIdToVinvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vinvoices', function (Blueprint $table) {
            // $table->decimal('total',20,2)->change();
            $table->text('note',500)->nullable()->after('total');
            $table->unsignedBigInteger('customer_id')->nullable()->after('note');
            $table->unsignedBigInteger('supplier_id')->nullable()->after('customer_id');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vinvoices', function (Blueprint $table) {
            $table->dropColumn('customer_id');
            $table->dropColumn('supplier_id');
            $table->dropColumn('note');
        });
    }
}
