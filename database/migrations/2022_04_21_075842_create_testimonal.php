<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestimonal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('testimonal'))
        {
            Schema::create('testimonal', function (Blueprint $table) {
                $table->id();
                $table->char('title',255);
                $table->char('grade', 255);
                $table->char('school', 255);
                $table->char('location', 255);
                $table->longText('description');
                $table->char('image', 255);
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
        Schema::dropIfExists('testimonal');
    }
}
