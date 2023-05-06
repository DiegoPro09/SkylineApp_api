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
        Schema::create('student_tutors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tutor_user_id');
            $table->unsignedBigInteger('student_user_id');
            $table->timestamps();

            $table->foreign('tutor_user_id')->references('id')->on('users');
            $table->foreign('student_user_id')->references('id')->on('user_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_tutors');
    }
};
