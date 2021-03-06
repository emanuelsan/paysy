<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amount',12,2)->unsigned();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->string('invoice_id');
            $table->string('sale_id');
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
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign('payments_customer_id_foreign');
        });
        Schema::drop('payments');
    }
}
