<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsersIdToSpecialServicesTable extends Migration
{
    public function up()
    {
        Schema::table('special_services', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

        });
    }

    public function down()
    {
        Schema::table('special_services', function (Blueprint $table) {
            $table->dropForeign('user_id');
        });
    }
}
