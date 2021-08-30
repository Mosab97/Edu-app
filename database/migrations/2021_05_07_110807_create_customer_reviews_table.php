<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('customer_reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('rate')->default(0);
            $table->string('title');
            $table->string('details');
            $table->string('customer_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_reviews');
    }
}
