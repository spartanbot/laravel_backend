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
        if(!Schema::hasTable('notification'))
        {
            Schema::create('notification', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->nullable();
                $table->integer('seller_id')->nullable();
                $table->string('message');
                $table->enum('type',array('ProductCreated','Enrol'));
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
        Schema::dropIfExists('notification');
    }
};
