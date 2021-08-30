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
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->integer('client_type')->default(\App\Models\User::client_type['CLIENT']);
            $table->string('url')->nullable();
            $table->boolean('notification')->default(false);
            $table->boolean('is_registered')->default(false);
            $table->integer('gender')->default(MALE);
            $table->integer('source')->default(\App\Models\User::ANDROID);
            $table->boolean('verified')->default(false);
            $table->float('rate', 2, 1)->default(0);
            $table->string('generatedCode')->nullable();
            $table->float('lat', 8, 5)->nullable();
            $table->float('lng', 8, 5)->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->enum('local', ['en', 'ar'])->default('ar');
            $table->date('dob')->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('ordered')->default(1);
            $table->boolean('draft')->default(0);
            $table->string('provider');
            $table->string('provider_id');
            $table->string('google_provider_id')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
            $table->unique(['phone', 'deleted_at']);
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
