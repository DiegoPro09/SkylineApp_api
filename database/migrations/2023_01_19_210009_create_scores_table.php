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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('period_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('assignament_id');
            $table->integer('first_score');
            $table->integer('second_score');
            $table->integer('dec_score')->nullable();
            $table->integer('mar_score')->nullable();
            $table->timestamps();

            $table->foreign('period_id')->references('id')->on('periods');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('assignament_id')->references('id')->on('assignaments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
};
