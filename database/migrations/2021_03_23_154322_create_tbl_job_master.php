<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblJobMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_masters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id');
            $table->string('job_master_name');
            $table->integer('summary_order');
            $table->string('summary_type');//category |customer
            $table->boolean('is_rank');
            $table->string('rank_code')->nullable();
            $table->integer('summary_group');// can|dien tich
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
        Schema::dropIfExists('job_masters');
    }
}
