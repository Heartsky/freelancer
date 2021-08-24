<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerToExpenseTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expense_transactions', function (Blueprint $table) {
            $table->string('customer_name')->nullable();
            $table->bigInteger('bank_account_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expense_transactions', function (Blueprint $table) {
            //
        });
    }
}
