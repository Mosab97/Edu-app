<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactUsTable extends Migration
{
    public function up()
    {
        Schema::create('contact_us', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('target')->default(\App\Models\ContactUs::target['Recruitment']);
            $table->string('how_did_you_hear_about_ingaz')->default(\App\Models\ContactUs::how_did_you_hear_about_ingaz['Facebook']);
            $table->text('message');
            $table->boolean('seen')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }


    public function down()
    {
        Schema::dropIfExists('contact_us');
    }
}
