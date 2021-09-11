<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('teacher_id');
            $table->string('video')->nullable();
            $table->string('image')->nullable();
            $table->double('price')->nullable();
            $table->double('students_number_max')->nullable();
            $table->double('number_of_live_lessons')->nullable();
            $table->double('number_of_exercises_and_games')->nullable();
            $table->integer('gender')->default(Gender['MALE']);
            $table->dateTime('course_date_and_time')->nullable();
            $table->string('what_will_i_learn')->nullable();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('age_id');
            $table->timestamps();
            $table->foreign('course_id')->on('courses')->references('id');
            $table->foreign('teacher_id')->on('teachers')->references('id');
            $table->foreign('level_id')->on('levels')->references('id');
            $table->foreign('age_id')->on('ages')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
