<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavigationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->length(11)->nullable();
            $table->integer('seller_id')->length(11)->nullable();
            $table->char('message', 255);
            $table->enum('type', array('ProductCreated','Enrol'));
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
        Schema::dropIfExists('navigation');
    }
}
