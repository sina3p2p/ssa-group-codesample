<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookVariationPriceHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_variation_price_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_variation_id')->notNull();
            $table->timestamp('sync_date')->useCurrent();
            $table->double('old_price')->notNull();
            $table->double('new_price')->notNull();
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
        Schema::dropIfExists('book_variation_price_history');
    }
}
