<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('resourse'))
        {
            Schema::create('resourse', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('seller_id');
                $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
                $table->char('resourse_title', 255);
                $table->longText('resourse_description');
                $table->integer('price')->length(11);
                $table->char('resourse_content', 4000);
                $table->integer('verify')->length(11);
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
        Schema::dropIfExists('resourse');
    }
}
