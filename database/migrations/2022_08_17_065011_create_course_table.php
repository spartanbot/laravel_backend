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
        if(!Schema::hasTable('course'))
        {
            Schema::create('course', function (Blueprint $table) {
                $table->id();
                $table->string('course_title');
                $table->text('course_description');
                $table->mediumText('subject');
                $table->integer('category_id')->nullable();
                $table->integer('language_id')->nullable();
                $table->string('age_group')->nullable();
                $table->string('category')->nullable();
                $table->string('language')->nullable();
                $table->string('grade_label');
                $table->string('course_banner')->nullable();
                $table->string('course_content')->nullable();
                $table->integer('course_fee');
                $table->string('affiliation')->nullable();
                $table->string('submission_type')->nullable();
                $table->string('difficulty')->nullable();
                $table->integer('seller_id');
                $table->integer('verify');
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
        Schema::dropIfExists('course');
    }
};
