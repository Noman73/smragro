<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('name',200);
            $table->string('branch_name',200)->nullable();
            $table->string('account_no',200);
            $table->string('account_code',200)->nullable();
            $table->text('details',200)->nullable();
            $table->decimal('open_ammount',20,2)->default(0);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('account_type')->default(0);
            $table->unsignedBigInteger('author_id');
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
        Schema::dropIfExists('banks');
    }
}
