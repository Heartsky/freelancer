<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerJobCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_job_categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('job_master_id')->nullable();
            $table->string('job_category_id')->nullable();
            $table->double('price_count')->nullable();
            $table->double('price_sqm')->nullable();
            $table->integer('invoice_order')->nullable();
            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->string('customer_alias')->nullable();
            $table->float('point')->nullable();
            $table->float('point_check')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_job_categories');
    }
}
