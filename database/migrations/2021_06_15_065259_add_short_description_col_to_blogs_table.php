<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShortDescriptionColToBlogsTable extends Migration
{
    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
//            $table->string('short_details')->nullable()->after('details');
        });
    }

    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
//            $table->dropColumn('short_details');
        });
    }
}
