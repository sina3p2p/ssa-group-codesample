<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeriodToBookVariations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_variations', function (Blueprint $table) {
            $table->date('start_discount')->nullable();
            $table->date('end_discount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_variations', function (Blueprint $table) {
            $table->dropColumn('start_discount');
            $table->dropColumn('end_discount');
        });
    }
}
