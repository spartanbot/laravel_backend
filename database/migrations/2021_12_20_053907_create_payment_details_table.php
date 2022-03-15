<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('payment_details'))
        {
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('cart_details');
            $table->string('cart_holder_name');
            $table->string('billing_address');
            $table->string('state');
            $table->string('zip');
            $table->string('vat_number');
            $table->string('discount_code');
            $table->string('amount');
            $table->enum('status', array('1','0'))->default('1');
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_details');
    }
}
