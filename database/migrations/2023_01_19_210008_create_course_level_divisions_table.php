<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_level_divisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('division_id');
            $table->unsignedBigInteger('specialization_id');
            $table->integer('year');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('level_id')->references('id')->on('levels');
            $table->foreign('division_id')->references('id')->on('divisions');
            $table->foreign('specialization_id')->references('id')->on('specializations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_level_divisions');
    }
};
