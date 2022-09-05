<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingsColumnToInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->tinyInteger('sale_by')->after('staff_note');
            $table->unsignedBigInteger('shipped_adress_id')->nullable()->after('sale_by');
            $table->decimal('cond_amount',20,2)->nullable()->after('shipped_adress_id');
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
            $table->dropColumn('sale_by');
            $table->dropColumn('shipped_adress_id');
            $table->dropColumn('cond_amount');
        });
    }
}
