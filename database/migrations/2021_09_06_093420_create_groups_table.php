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
            $table->string('image')->nullable();
            $table->double('price')->nullable();
            $table->double('students_number_max')->nullable();
            $table->integer('level')->default(\App\Models\Group::levels['level One']);
            $table->integer('gender')->default(Gender['MALE']);
            $table->string('time')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->timestamps();
            $table->foreign('course_id')->on('courses')->references('id');
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
