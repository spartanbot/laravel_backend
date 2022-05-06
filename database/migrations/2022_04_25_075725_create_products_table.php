<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->string('product_name');
            $table->integer('price')->length(11);
            $table->string('category_name');
            $table->string('language');
            $table->string('subject');
            $table->string('grade');
            $table->string('affiliation');
            $table->string('submission');
            $table->string('difficulty');
            $table->string('description');
            $table->string('product_image');
            $table->string('product_gallery');
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
        Schema::dropIfExists('products');
    }
}
