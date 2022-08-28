<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSalesPurchaseProductionComboprodColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger('sale')->defult(0);
            $table->tinyInteger('purchase')->default(0);
            $table->tinyInteger('production')->default(0);
            $table->tinyInteger('combo')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->dropColumn('product_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sale');
            $table->dropColumn('purchase');
            $table->dropColumn('production');
            $table->dropColumn('combo');
            $table->tinyInteger('product_type');
        });
    }
}
