<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_transactions', function (Blueprint $table) {
            $table->id();
            $table->date("expense_date")->nullable()->comment("ngay phat sinh giao dich");
            $table->date("input_date")->nullable()->comment('ngay nhap lieu he thong');
            $table->string("description")->nullable();
            $table->bigInteger("branch_id")->nullable();
            $table->bigInteger("account_id")->nullable();
            $table->double("amount")->nullable();
            $table->string('transaction_code')->nullable();
            $table->string('type')->nullable()->comment("debited(chi)/credited (thu)");
            $table->integer('expense_group')->nullable()->comment('1: bank , 2: cash, 3: epense not real paid, 4: visa');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->date('deleted_on')->nullable();
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
        Schema::dropIfExists('expense_transactions');
    }
}
