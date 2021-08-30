<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{

    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('ordered')->default(1);
            $table->boolean('draft')->default(0);
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
