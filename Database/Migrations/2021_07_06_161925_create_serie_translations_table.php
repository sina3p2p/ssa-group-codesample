<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSerieTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serie_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serie_id')->constrained("series")->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->unique(['serie_id','locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('serie_translations');
    }
}
