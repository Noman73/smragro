<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name',100)->nullable();
            $table->string('name',100);
            $table->decimal('opening_balance',16,2)->default(0);
            $table->decimal('maximum_due',16,2)->nullable();
            $table->string('phone',25)->unique();
            $table->string('email',100)->nullable()->unique();
            $table->string('birth_date',100)->nullable();
            $table->string('nid',100)->nullable();
            $table->string('adress',100)->nullable();
            $table->integer('status')->default(1);
            $table->string('image',100)->nullable();
            // type 1 is regular and 0 is walking customer
            $table->tinyInteger('type');
            $table->integer('author_id');
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
        Schema::dropIfExists('customers');
    }
}
