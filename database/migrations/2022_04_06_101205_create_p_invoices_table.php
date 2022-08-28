<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('dates',30);
            $table->string('issue_date',30)->nullable();
            $table->string('chalan_no',200)->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->integer('total_item');
            $table->decimal('vat',16,2)->nullable();
            $table->decimal('transport',16,2)->nullable();
            $table->decimal('total_payable',16,2);
            $table->decimal('total',16,2);
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->tinyInteger('purchase_type');
            $table->unsignedInteger('action_id')->default(0);
            $table->unsignedInteger('user_id');
            $table->text('note',500)->nullable();
            $table->text('staff_note',500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('p_invoices');
    }
}
