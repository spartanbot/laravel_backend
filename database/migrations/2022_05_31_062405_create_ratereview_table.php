<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatereviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('ratereview'))

        {
            Schema::create('ratereview', function (Blueprint $table) {
            $table->id()->bigInteger();
            $table->bigInteger('user_id');
            $table->Integer('course_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('course')->onDelete('cascade');
            $table->Integer('rating');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('title')->nullable();
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
        Schema::dropIfExists('ratereview');
    }
}
