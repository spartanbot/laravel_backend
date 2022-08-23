<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('order_item'))
        {
            Schema::create('order_item', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id')->nullable();
                $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
                $table->unsignedBigInteger('course_id')->nullable();
                $table->foreign('course_id')->references('id')->on('course')->onDelete('cascade');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->bigInteger('seller_id');
                $table->string('course_name');
                $table->integer('course_fee');
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
        Schema::dropIfExists('order_item');
    }
};
