<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassCatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_cats', function (Blueprint $table) {
            $table->id();
            $table->integer('class_id');
            $table->integer('first_id');
            $table->integer('second_id');
            $table->integer('third_id');
            $table->integer('level_id');
            $table->string('course_number', 10);
            $table->integer('type_id');
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
        Schema::dropIfExists('class_cats');
    }
}
