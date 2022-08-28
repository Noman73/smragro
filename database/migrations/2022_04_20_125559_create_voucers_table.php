<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoucersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('person_id')->nullable();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('pinvoice_id')->nullable();
            $table->unsignedBigInteger('ledger_id')->nullable();
            $table->unsignedBigInteger('subledger_id')->nullable();
            $table->unsignedBigInteger('action_id')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->unsignedBigInteger('journal_inv_id')->nullable();
            $table->unsignedBigInteger('cheque_no')->nullable();
            $table->tinyInteger('cheque_status')->nullable();
            $table->string('cheque_issue_date',200)->nullable();
            $table->string('cheque_photo',200)->nullable();
            $table->string('date',200)->nullable();
            $table->string('transaction_name',200)->nullable();
            $table->decimal('debit')->default(0);
            $table->decimal('credit')->default(0);
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            $table->foreign('account_id')->references('id')->on('banks')->onDelete('cascade');
            $table->foreign('pinvoice_id')->references('id')->on('p_invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucers');
    }
}
