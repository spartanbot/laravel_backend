<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseInstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_instructors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('reference_one_name');
            $table->string('reference_one_email');
            $table->string('reference_one_phonenumber');
            $table->string('reference_two_name');
            $table->string('reference_two_email');
            $table->string('reference_two_phonenumber');
            $table->enum('status', array('1','0'))->default('1');
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
        Schema::dropIfExists('course_instructors');
    }
}
