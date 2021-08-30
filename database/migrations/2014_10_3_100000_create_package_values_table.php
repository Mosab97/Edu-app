<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageValuesTable extends Migration
{

    public function up()
    {
        Schema::create('package_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id')->nullable();
//            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('value')->nullable();
            $table->timestamps();
            $table->foreign('package_id')->references('id')->on('packages')->cascadeOnDelete();
//            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_values');
    }
}
