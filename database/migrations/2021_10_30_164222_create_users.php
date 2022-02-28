<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('user_name');
            $table->string('user_email')->unique();
            $table->string('password');
            $table->string('location');
            $table->string('preferred_language');
            $table->string('i_am_a');
            $table->string('api_token', 80)->unique()
                        ->nullable()
                        ->default(null);
            $table->enum('user_status', array('1','0'))->default('1');
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
        Schema::dropIfExists('users');
    }
}
