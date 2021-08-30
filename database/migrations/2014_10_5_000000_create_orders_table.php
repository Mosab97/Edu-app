<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{

    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->string('project_activity')->nullable();
            $table->integer('company_offers')->nullable();
            $table->string('profile')->nullable();
            $table->string('catalog')->nullable();
            $table->integer('employee_number')->nullable();
            $table->integer('size_of_the_estimated_revenue_of_the_project')->nullable();
            $table->integer('size_of_the_estimated_expenses_of_the_project')->nullable();
            $table->integer('monthly_budget_allocated_to_the_accounting_program')->nullable();
            $table->integer('lang')->nullable();
            $table->string('other_helpful_attachments')->nullable();
            $table->string('details')->nullable();
            $table->string('describe_your_need_for_the_program')->nullable();

//            Service 2
            $table->string('work_activity')->nullable();
            $table->string('current_accounting_program')->nullable();
            $table->string('mechanism_of_action')->nullable();
            $table->string('service_description')->nullable();
            $table->string('total_cost')->nullable();


            //            Service 3
            $table->string('training_title')->nullable();
            $table->integer('number_people')->nullable();
            $table->integer('number_of_hours_required')->nullable();
            $table->integer('training_requirements')->nullable();


            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('service_id')->references('id')->on('services')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
