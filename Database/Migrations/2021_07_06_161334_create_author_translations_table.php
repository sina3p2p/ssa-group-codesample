<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('author_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained("authors")->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('country')->nullable();
            $table->string('fullname')->nullable();
            $table->text('description')->nullable();
            $table->unique(['author_id','locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('author_translations');
    }
}
