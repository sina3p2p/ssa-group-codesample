<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->integer('author_id')->default(0);
            $table->integer('serie_id')->default(0);
            $table->string('isbn')->nullable();
            $table->string("min_picture")->nullable();
            $table->json('pictures')->nullable();
            $table->boolean('is_bestseller')->default(false);
            $table->integer('category_id')->default(0);
            $table->integer('year')->default(0);
            $table->string('publisher')->nullable();
            $table->string('translator')->nullable();
            $table->string('language')->nullable();
            $table->integer('block')->default(0);
            $table->integer('year_range')->default(0);
            $table->json('details')->nullable();
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
        Schema::dropIfExists('books');
    }
}
