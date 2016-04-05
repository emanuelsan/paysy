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
            $table->string('id')->primary();
            $table->decimal('amount',12,2)->unsigned();
            $table->string('card_id')->nullable();
            $table->string('invoice_number');
            $table->timestamps();

            $table->foreign('card_id')->references('card_id')->on('customers')->onDelete('set null');
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
            $table->dropForeign('payments_card_id_foreign');
        });
        Schema::drop('payments');
    }
}
