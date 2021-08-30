<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialServicesTable extends Migration
{
    public function up()
    {
        Schema::create('special_services', function (Blueprint $table) {
            $table->id();
            $table->string('project_title')->nullable();
            $table->string('project_details')->nullable();
            $table->integer('service_type')->nullable();
            $table->string('expected_budget')->nullable();
            $table->string('expected_delivery_time')->nullable();
            $table->string('other_help_attachments')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('special_services');
    }
}
