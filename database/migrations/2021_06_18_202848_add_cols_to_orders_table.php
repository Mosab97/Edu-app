<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->double('commission')->after('service_id')->default(0);
            $table->double('commission_cost')->after('commission')->default(0);
            $table->double('total_price')->after('commission_cost')->default(0);
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('commission');
            $table->dropColumn('commission_cost');
            $table->dropColumn('total_price');
        });
    }
}
