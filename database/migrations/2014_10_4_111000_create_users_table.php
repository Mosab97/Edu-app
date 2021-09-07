<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();//->unique();
            $table->string('username')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->integer('gender')->default(MALE);
            $table->boolean('verified')->default(false);
            $table->string('generatedCode')->nullable();
            $table->float('lat', 8, 5)->nullable();
            $table->float('lng', 8, 5)->nullable();
            $table->string('image')->nullable();
            $table->enum('local', ['en', 'ar'])->default('ar');
            $table->date('dob')->nullable();
            $table->string('password')->nullable();
            $table->string('provider');
            $table->string('provider_id');
            $table->string('google_provider_id')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
            $table->unique(['phone', 'deleted_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}