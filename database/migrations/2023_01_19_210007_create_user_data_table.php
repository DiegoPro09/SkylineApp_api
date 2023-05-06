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
        Schema::create('user_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('book');
            $table->string('invoice');
            $table->string('dni');
            $table->string('adress');
            $table->date('inscription_date');
            $table->date('birthday_date');
            $table->date('place_of_birth');
            $table->string('nacionality');
            $table->string('province');
            $table->integer('mobile_phone');
            $table->integer('fixed_phone');
            $table->unsignedBigInteger('genre_id');
            $table->string('medical_record');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('genre_id')->references('id')->on('genres');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
