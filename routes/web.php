<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();
Route::get('/export', 'App\Http\Controllers\ExportController@index')->name('export');

Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
    Route::get('profile', ['as' => 'profile.show', 'uses' => 'App\Http\Controllers\ProfileController@show']);
	Route::get('profile-edit', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile-update', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade');
	 Route::get('map', function () {return view('pages.maps');})->name('map');
	 Route::get('icons', function () {return view('pages.icons');})->name('icons');
	 Route::get('table-list', function () {return view('pages.tables');})->name('table');
    Route::get('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@editPassword']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

	Route::get('export/team-summary',['as' => 'export.team_summary', 'uses' => 'App\Http\Controllers\ExportController@teamSummaryWorking']);
    Route::post('export/team-summary',['as' => 'export.team_summary', 'uses' => 'App\Http\Controllers\ExportController@exportTeamSummaryWorking']);
    Route::get('export/customer-summary',['as' => 'export.customer_summary', 'uses' => 'App\Http\Controllers\ExportController@customerSummaryWorking']);
    Route::post('export/customer-summary',['as' => 'export.customer_summary', 'uses' => 'App\Http\Controllers\ExportController@exportCustomerSummaryWorking']);

    Route::get('export/invoice-data', ['as' => 'export.customer_invoice_type', 'uses' => 'App\Http\Controllers\ExportController@getInvoiceData']);

    Route::get('export/summary-work',['as' => 'export.summary_work', 'uses' => 'App\Http\Controllers\ExportController@summaryWorking']);

    Route::get('export/customer_invoice',['as' => 'export.customer_invoice', 'uses' => 'App\Http\Controllers\ExportController@customerInvoice']);
    Route::post('export/customer_invoice',['as' => 'export.export_customer_invoice', 'uses' => 'App\Http\Controllers\ExportController@exportCustomerInvoice']);

    Route::get('company-management', ['as' => 'master.company_management', 'uses' => 'App\Http\Controllers\MasterManagementController@companyList']);
    Route::post('company-create', ['as' => 'master.company_create', 'uses' => 'App\Http\Controllers\MasterManagementController@companyCreate']);
    Route::get('company-create', ['as' => 'master.company_create', 'uses' => 'App\Http\Controllers\MasterManagementController@companyCreate']);
    Route::get('company-management/{id}', ['as' => 'master.company_detail', 'uses' => 'App\Http\Controllers\MasterManagementController@companyDetail']);
    Route::post('company-management/{id}', ['as' => 'master.company_update', 'uses' => 'App\Http\Controllers\MasterManagementController@companyUpdate']);
    Route::post('company-management/{id}/bank-account', ['as' => 'master.company_update_bank_account', 'uses' => 'App\Http\Controllers\MasterManagementController@companyUpdateBankAccount']);

    Route::get('invoice-money', ['as' => 'invoice.invoice_mooney_create', 'uses' => 'App\Http\Controllers\InvoiceController@addInvoice']);
    Route::post('invoice-money', ['as' => 'invoice.invoice_mooney_save', 'uses' => 'App\Http\Controllers\InvoiceController@saveInvoice']);



    Route::get('company-management/{id}/delete', ['as' => 'master.company_delete', 'uses' => 'App\Http\Controllers\MasterManagementController@companyDelete']);

    Route::get('user-management', ['as' => 'master.user_management', 'uses' => 'App\Http\Controllers\MasterManagementController@userList']);
    Route::get('user-management/create', ['as' => 'master.user_create', 'uses' => 'App\Http\Controllers\MasterManagementController@userCreate']);
    Route::post('user-management/create', ['as' => 'master.user_create', 'uses' => 'App\Http\Controllers\MasterManagementController@userCreate']);
    Route::get('user-management/{id}', ['as' => 'master.user_detail', 'uses' => 'App\Http\Controllers\MasterManagementController@userDetail']);
    Route::post('user-management/{id}/edit', ['as' => 'master.user_edit', 'uses' => 'App\Http\Controllers\MasterManagementController@userUpdate']);
    Route::get('user-management/{id}/delete', ['as' => 'master.user_delete', 'uses' => 'App\Http\Controllers\MasterManagementController@userDelete']);

    Route::get('team-management', ['as' => 'master.team_management', 'uses' => 'App\Http\Controllers\MasterManagementController@teamList']);
    Route::post('team-create', ['as' => 'master.team_create', 'uses' => 'App\Http\Controllers\MasterManagementController@teamCreate']);
    Route::get('team-create', ['as' => 'master.team_create', 'uses' => 'App\Http\Controllers\MasterManagementController@teamCreate']);
    Route::get('team-management/{id}', ['as' => 'master.team_detail', 'uses' => 'App\Http\Controllers\MasterManagementController@teamDetail']);
    Route::post('team-management/{id}', ['as' => 'master.team_update', 'uses' => 'App\Http\Controllers\MasterManagementController@teamUpdate']);
    Route::get('team-management/{id}/delete', ['as' => 'master.team_delete', 'uses' => 'App\Http\Controllers\MasterManagementController@teamDelete']);



    Route::get('job-rank-management/{masterId}', ['as' => 'master_job_rank_list', 'uses' => 'App\Http\Controllers\MasterManagementController@masterJobRank']);
    Route::get('job-rank-create/{masterId}', ['as' => 'master_job_rank_create', 'uses' => 'App\Http\Controllers\MasterManagementController@createMasterJobRank']);
    Route::post('job-rank-create/{masterId}', ['as' => 'master_job_rank_save', 'uses' => 'App\Http\Controllers\MasterManagementController@saveMasterJobRank']);

    Route::get('job-rank-create/{masterId}/edit/{id}', ['as' => 'master_job_rank_edit', 'uses' => 'App\Http\Controllers\MasterManagementController@editMasterJobRank']);
    Route::post('job-rank-create/{masterId}/update/{id}', ['as' => 'master_job_rank_update', 'uses' => 'App\Http\Controllers\MasterManagementController@updateMasterJobRank']);

    Route::get('job-rank-create/{masterId}/delete/{id}', ['as' => 'master_job_rank_delete', 'uses' => 'App\Http\Controllers\MasterManagementController@deleteMasterJobRank']);

    Route::get('bank-account-management', ['as' => 'master.bank_account_management', 'uses' => 'App\Http\Controllers\MasterManagementController@bankAccountList']);
    Route::post('bank-account-create', ['as' => 'master.bank_account_create', 'uses' => 'App\Http\Controllers\MasterManagementController@bankAccountCreate']);
    Route::get('bank-account-create', ['as' => 'master.bank_account_create', 'uses' => 'App\Http\Controllers\MasterManagementController@bankAccountCreate']);
    Route::get('bank-account-management/{id}', ['as' => 'master.bank_account_detail', 'uses' => 'App\Http\Controllers\MasterManagementController@bankAccountDetail']);
    Route::post('bank-account-management/{id}', ['as' => 'master.bank_account_update', 'uses' => 'App\Http\Controllers\MasterManagementController@bankAccountUpdate']);
    Route::get('bank-account-management/{id}/delete', ['as' => 'master.bank_account_delete', 'uses' => 'App\Http\Controllers\MasterManagementController@bankAccountDelete']);

    Route::get('staff-management', ['as' => 'master.staff_management', 'uses' => 'App\Http\Controllers\MasterManagementController@staffList']);

    Route::any('staff-management-create', ['as' => 'master.staff_management_create', 'uses' => 'App\Http\Controllers\MasterManagementController@staffCreate']);

    Route::get('staff-management/{id}', ['as' => 'master.staff_detail', 'uses' => 'App\Http\Controllers\MasterManagementController@staffDetail']);
    Route::post('staff-management/{id}', ['as' => 'master.staff_update', 'uses' => 'App\Http\Controllers\MasterManagementController@staffUpdate']);
    Route::get('staff-management/{id}/delete', ['as' => 'master.staff_delete', 'uses' => 'App\Http\Controllers\MasterManagementController@staffDelete']);


    Route::any('staff-management-import', ['as' => 'master.staff_import', 'uses' => 'App\Http\Controllers\MasterManagementController@staffImport']);

    Route::get('customer-management', ['as' => 'master.customer_management', 'uses' => 'App\Http\Controllers\MasterManagementController@customerManagement']);


    Route::any('job-management/{companyId}', ['as' => 'master_job_management', 'uses' => 'App\Http\Controllers\MasterManagementController@companyJob']);



    Route::any('job-management-delete/{companyId}/{id}', ['as' => 'master_job_management_delete', 'uses' => 'App\Http\Controllers\MasterManagementController@companyJobDelete']);


    Route::any('customer-management/job/{masterId}', ['as' => 'customer_job_management', 'uses' => 'App\Http\Controllers\MasterManagementController@customerJob']);


    Route::any('category-job-delete/{masterId}/{id}', ['as' => 'category_job_delete', 'uses' => 'App\Http\Controllers\MasterManagementController@customerJobDelete']);

    Route::any('customer-management/job-cat/{customerId}', ['as' => 'customer_job_category', 'uses' => 'App\Http\Controllers\MasterManagementController@customerJobCategory']);

    Route::any('category-job-customer-delete/{customerId}/{id}', ['as' => 'customer_job_category_delete', 'uses' => 'App\Http\Controllers\MasterManagementController@customerJobCategoryDelete']);

    Route::get('get-job-master-by-company', ['as' => 'master.job_master_by_company', 'uses' => 'App\Http\Controllers\MasterManagementController@customerJobByCompany']);
    Route::get('get-job-by-master', ['as' => 'master.job_by_master', 'uses' => 'App\Http\Controllers\MasterManagementController@customerJobByMaster']);

    Route::any('customer-alias-name/{customerId}', ['as' => 'customer_alias_name', 'uses' => 'App\Http\Controllers\MasterManagementController@customerAliasName']);

    Route::any('customer-alias-name-delete/{customerId}/{id}', ['as' => 'customer_alias_delete', 'uses' => 'App\Http\Controllers\MasterManagementController@customerAliasNameDelete']);

    Route::any('customer-management/create', ['as' => 'master.customer_create', 'uses' => 'App\Http\Controllers\MasterManagementController@customerCreate']);

    Route::any('customer-management/delete/{id}', ['as' => 'master.customer_delete', 'uses' => 'App\Http\Controllers\MasterManagementController@customerDelete']);


    Route::any('customer-management/{id}', ['as' => 'master.customer_detail', 'uses' => 'App\Http\Controllers\MasterManagementController@customerDetail']);
    Route::post('customer-management/{id}/bank-account', ['as' => 'master.customer_update_bank_account', 'uses' => 'App\Http\Controllers\MasterManagementController@customerUpdateBankAccount']);

    Route::get('employee-management', ['as' => 'master.employee_management', 'uses' => 'App\Http\Controllers\MasterManagementController@customerManagement']);
    Route::get('st-management', ['as' => 'master.st_management', 'uses' => 'App\Http\Controllers\MasterManagementController@customerManagement']);
    Route::get('tsv-management', ['as' => 'master.tsv_management', 'uses' => 'App\Http\Controllers\MasterManagementController@customerManagement']);

    Route::any('upload-data', ['as' => 'upload_data', 'uses' => 'App\Http\Controllers\UploadController@upload']);

    // Union fee
    Route::get('union-fee', ['as' => 'union_fee', 'uses' => 'App\Http\Controllers\UnionFeeController@index']);
    Route::get('union-fee-export', ['as' => 'union_fee_export', 'uses' => 'App\Http\Controllers\UnionFee@export']);

    Route::get('expense-report', ['as' => 'expense.report', 'uses' => 'App\Http\Controllers\ExpenseController@report']);
    Route::post('expense-report', ['as' => 'expense.export_report', 'uses' => 'App\Http\Controllers\ExpenseController@exportReport']);

    Route::get('expense-cash-transaction', ['as' => 'expense.cash_transaction_list', 'uses' => 'App\Http\Controllers\ExpenseController@cashTransactionList']);
    Route::get('expense-cash-transaction/create', ['as' => 'expense.cash_transaction_add', 'uses' => 'App\Http\Controllers\ExpenseController@addCashTransaction']);
    Route::post('expense-cash-transaction/create', ['as' => 'expense.cash_transaction_save', 'uses' => 'App\Http\Controllers\ExpenseController@saveCashTransaction']);
    Route::get('expense-cash-transaction/{id}/edit', ['as' => 'expense.cash_transaction_edit', 'uses' => 'App\Http\Controllers\ExpenseController@editCashTransaction']);
    Route::post('expense-cash-transaction/{id}/edit', ['as' => 'expense.cash_transaction_update', 'uses' => 'App\Http\Controllers\ExpenseController@updateCashTransaction']);
    Route::get('expense-cash-transaction/{id}/delete', ['as' => 'expense.cash_transaction_delete', 'uses' => 'App\Http\Controllers\ExpenseController@deleteCashTransaction']);

    Route::get('expense-bank-transaction', ['as' => 'expense.bank_transaction_list', 'uses' => 'App\Http\Controllers\ExpenseController@bankTransactionList']);
    Route::get('expense-bank-transaction/create', ['as' => 'expense.bank_transaction_add', 'uses' => 'App\Http\Controllers\ExpenseController@addBankTransaction']);
    Route::post('expense-bank-transaction/create', ['as' => 'expense.bank_transaction_save', 'uses' => 'App\Http\Controllers\ExpenseController@saveBankTransaction']);
    Route::get('expense-bank-transaction/{id}/detail', ['as' => 'expense.bank_transaction_edit', 'uses' => 'App\Http\Controllers\ExpenseController@editBankTransaction']);
    Route::post('expense-bank-transaction/{id}/edit', ['as' => 'expense.bank_transaction_update', 'uses' => 'App\Http\Controllers\ExpenseController@updateBankTransaction']);
    Route::get('expense-bank-transaction/{id}/delete', ['as' => 'expense.bank_transaction_delete', 'uses' => 'App\Http\Controllers\ExpenseController@deleteBankTransaction']);

    Route::get('expense-bank-transaction/export', ['as' => 'expense.bank_transaction_export', 'uses' => 'App\Http\Controllers\ExpenseController@reportBankTransaction']);
    Route::post('expense-bank-transaction/export', ['as' => 'expense.bank_transaction_export', 'uses' => 'App\Http\Controllers\ExpenseController@exportBankTransaction']);


    Route::get('expense-visa-paid', ['as' => 'expense.visa_paid_list', 'uses' => 'App\Http\Controllers\ExpenseController@visaPaidList']);
    Route::get('expense-visa-paid/create', ['as' => 'expense.visa_paid_add', 'uses' => 'App\Http\Controllers\ExpenseController@addVisaPaid']);
    Route::post('expense-visa-paid/create', ['as' => 'expense.visa_paid_save', 'uses' => 'App\Http\Controllers\ExpenseController@saveVisaPaid']);
    Route::get('expense-visa-paid/{id}/edit', ['as' => 'expense.visa_paid_edit', 'uses' => 'App\Http\Controllers\ExpenseController@editVisaPaid']);
    Route::post('expense-visa-paid/{id}/edit', ['as' => 'expense.visa_paid_update', 'uses' => 'App\Http\Controllers\ExpenseController@updateVisaPaid']);
    Route::get('expense-visa-paid/{id}/delete', ['as' => 'expense.visa_paid_delete', 'uses' => 'App\Http\Controllers\ExpenseController@deleteVisaPaid']);


    Route::get('report-finance', ['as' => 'finance.report', 'uses' => 'App\Http\Controllers\FinanceController@reportFinance']);
    Route::post('report-finance/export', ['as' => 'finance.export', 'uses' => 'App\Http\Controllers\FinanceController@exportFinance']);

    Route::get('report-revenue', ['as' => 'revenue.report', 'uses' => 'App\Http\Controllers\RevenueController@reportRevenue']);
    Route::post('report-revenue/export', ['as' => 'revenue.export', 'uses' => 'App\Http\Controllers\RevenueController@exportRevenue']);

    Route::get('expense-expense-not-real-paid', ['as' => 'expense.expense_nrp_list', 'uses' => 'App\Http\Controllers\ExpenseController@expenseNrpList']);
    Route::get('expense-expense-not-real-paid/create', ['as' => 'expense.expense_nrp_add', 'uses' => 'App\Http\Controllers\ExpenseController@addExpenseNrp']);
    Route::post('expense-expense-not-real-paid/create', ['as' => 'expense.expense_nrp_save', 'uses' => 'App\Http\Controllers\ExpenseController@saveExpenseNrp']);
    Route::get('expense-expense-not-real-paid/{id}/edit', ['as' => 'expense.expense_nrp_edit', 'uses' => 'App\Http\Controllers\ExpenseController@editExpenseNrp']);
    Route::post('expense-expense-not-real-paid/{id}/edit', ['as' => 'expense.expense_nrp_update', 'uses' => 'App\Http\Controllers\ExpenseController@updateExpenseNrp']);
    Route::get('expense-expense-not-real-paid/{id}/delete', ['as' => 'expense.expense_nrp_delete', 'uses' => 'App\Http\Controllers\ExpenseController@deleteExpenseNrp']);

// branch route

    Route::any('branch-management/{companyId}', ['as' => 'master_branch_management', 'uses' => 'App\Http\Controllers\MasterManagementController@companyBranch']);

    Route::any('branch-management-delete/{companyId}', ['as' => 'master_branch_management_delete', 'uses' => 'App\Http\Controllers\MasterManagementController@companyBranchDelete']);

    Route::any('get-branch-by-company-id', ['as' => 'master.get_branch_by_company', 'uses' => 'App\Http\Controllers\MasterManagementController@companyBranchById']);

    Route::any('customer-merchant', ['as' => 'customer_merchant', 'uses' => 'App\Http\Controllers\MasterManagementController@customerMechant']);

    Route::any('customer-merchant-list/{customerId}', ['as' => 'customer_merchant_list', 'uses' => 'App\Http\Controllers\MasterManagementController@customerMechantList']);

    Route::any('customer-merchant-list-remove/{customerId}', ['as' => 'customer_merchant_list_remove', 'uses' => 'App\Http\Controllers\MasterManagementController@customerMechantListRemove']);


});

