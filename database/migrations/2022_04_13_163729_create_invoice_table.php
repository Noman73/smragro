<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('shipping_id')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->unsignedInteger('action_id')->default(0);
            $table->unsignedInteger('author_id');
            $table->string('dates',30);
            $table->string('issue_date',30)->nullable();
            $table->string('chalan_no',200)->nullable();
            $table->integer('total_item');
            $table->decimal('vat',16,2)->default(0);
            $table->decimal('discount',16,2)->default(0);
            $table->decimal('transport',16,2)->default(0);
            $table->decimal('total_payable',16,2);
            $table->decimal('total',16,2);
            $table->tinyInteger('sale_type');
            $table->tinyInteger('discount_type');
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
        Schema::dropIfExists('invoices');
    }
}
