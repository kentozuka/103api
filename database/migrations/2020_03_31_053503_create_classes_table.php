<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->integer('dept_id');
            $table->text('title');
            $table->tinyInteger('credit');
            $table->string('eligible', 5);
            // ここ大丈夫か再確認する
            $table->string('category_jp', 50);
            $table->string('category_en', 100);
            // category ne
            $table->integer('level_id');
            $table->integer('language_id');
            $table->integer('term_id');
            $table->integer('campus_id');
            $table->boolean('is_open');
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
        Schema::dropIfExists('classes');
    }
}
