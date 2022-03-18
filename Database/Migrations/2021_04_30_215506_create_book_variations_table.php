<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_variations', function (Blueprint $table) {
            $table->id();
            $table->string('variation')->nullable();
            $table->integer('book_id')->default(0);
            $table->string("vendor_id", 500)->nullable();
            $table->float('price')->default(0);
            $table->float('discount')->default(0);
            // $table->json('specifications')->nullable();
            $table->integer('last_update')->default(0);
            $table->unsignedBigInteger('stock_count')->default(0);
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
        Schema::dropIfExists('book_variations');
    }
}
