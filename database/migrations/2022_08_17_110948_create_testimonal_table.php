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
        if(!Schema::hasTable('testimonal'))
        {
            Schema::create('testimonal', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('grade');
                $table->string('school');
                $table->string('location');
                $table->text('description');
                $table->string('image');
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
};
