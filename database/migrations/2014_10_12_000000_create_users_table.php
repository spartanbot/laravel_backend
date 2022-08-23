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
        if(!Schema::hasTable('users'))
        {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('full_name');
                $table->string('user_name');
                $table->string('user_email')->unique();
                $table->text('description')->nullable();
                $table->string('password');
                $table->string('phone');
                $table->string('gender');
                $table->string('location');
                $table->string('preferred_language');
                $table->string('i_am_a');
                $table->string('affiliation');
                $table->string('subject');
                $table->string('age_group');
                $table->string('talent')->nullable();
                $table->string('sample_content')->nullable();
                $table->string('organization')->nullable();
                $table->string('seller_ref_name')->nullable();
                $table->string('seller_ref_email')->nullable();
                $table->string('seller_ref_phonenumber')->nullable();
                $table->string('seller_ref_two_name')->nullable();
                $table->string('seller_ref_two_email')->nullable();
                $table->string('seller_ref_two_phonenumber')->nullable();
                $table->string('resourse_name')->nullable();
                $table->string('resourse_one_name')->nullable();
                $table->string('resourse_one_email')->nullable();
                $table->string('resourse_one_phonenumber')->nullable();
                $table->string('resourse_two_name')->nullable();
                $table->string('resourse_two_email')->nullable();
                $table->string('resourse_two_phonenumber')->nullable();
                $table->string('api_token')->nullable();
                $table->tinyInteger('verified')->default(0);
                $table->text('token')->nullable();
                $table->enum('role',array('admin','user','seller'))->nullable();
                $table->string('user_profile')->nullable();
                $table->tinyInteger('switch')->nullable();
                $table->integer('approved_by_admin')->nullable();
                $table->enum('user_status',array('1','0'))->default(1);
                $table->string('stripe_publish_key')->nullable();
                $table->string('stripe_secret_key')->nullable();
                //$table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
