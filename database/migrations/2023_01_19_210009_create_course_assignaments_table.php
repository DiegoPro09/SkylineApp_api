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
        Schema::create('course_assignaments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('c_l_d_id');
            $table->unsignedBigInteger('assignament_id');
            $table->unsignedBigInteger('specialization_id');
            $table->integer('order');
            $table->timestamps();

            $table->foreign('c_l_d_id')->references('id')->on('course_level_divisions');
            $table->foreign('assignament_id')->references('id')->on('assignaments');
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
        Schema::dropIfExists('course_assignaments');
    }
};
