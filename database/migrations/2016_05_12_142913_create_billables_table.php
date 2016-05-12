<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('quantity')->unsigned();
            $table->decimal('price',12,2)->unsigned();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billables', function (Blueprint $table) {
            $table->dropForeign('billables_customer_id_foreign');
        });
        Schema::drop('billables');
    }
}
