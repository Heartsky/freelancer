<?php

namespace App\Http\Controllers;

use App\Facades\WorkflowExport;
use App\Models\BankAccount;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\ExpenseTransaction;
use App\Models\InvoiceImport;
use App\Models\InvoiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\DataImport;
use Illuminate\Support\Facades\Auth;


class ExpenseController extends Controller
{


    public function report(Request $request) {

        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();

        return view('expense.export_expense',[
            'branches' => $listBranch,
            'currency' => config('role.currency_list')
        ]);




    }
    public function exportReport(Request $request) {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }

        $listBranch = $this->getValidBranch();
        $branchIds = array_keys($listBranch);
        $branchId = $request->input('branch_id');
        $branch = Branch::find($branchId);
        if (!in_array($branchId, $branchIds) || empty($branch)) {
            return back(401);
        }
        $startDate = $request->input('start_date', '');
        $endDate = $request->input('end_date', '');
        if (!empty($startDate)) {
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));
        }
        $currency = $request->input('currency');
        $param = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'balance' => $request->input('balance'),
            'branch_id' => $branchId,
            'currency' => $currency
        ];
        $path = WorkflowExport::exportExpenseBranch( $param);

        //   dd($jobs->toArray());
        $filename = "cash_at_".$branch->code."_".$currency."_".date("ymd").'.xlsx';
        return response()->download($path, $filename, []);


    }

    public function saveInvoice(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }

        $customer =  Customer::find()->toArray();
        $param = [
            'start_date' => date('Y-m-d', strtotime( $request->input('start_date'))),
            'end_date' => $request->input('end_date'),
            'branch_id' => $request->input('branch_id'),

        ];
        InvoiceImport::create($param);
        return back();
    }


    public function cashTransactionList(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);

        $data = ExpenseTransaction::whereIn('branch_id',$branchId )->where('expense_group', 2)->whereNull('deleted_on')->paginate(10);
        return view('expense.cash_transaction_list',['data' => $data]);
    }
    public function addCashTransaction(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();

        return view('expense.add_cash_transaction',[
            'branches' => $listBranch,
            'currency' => config('role.currency_list')

        ]);

    }
    public function saveCashTransaction(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $param = [
            'type' => $request->input('type'),
            'expense_date' =>date('Y-m-d', strtotime( $request->input('expense_date'))),
            'branch_id' => $request->input('branch_id'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'currency' => $request->input('currency'),
            'rate' => $request->input('rate'),
            'expense_group' => 2,
            'created_by' => $user->id


        ];
        ExpenseTransaction::create($param);
        return back();

    }
    public function editCashTransaction(Request $request, $id)
    {

        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);
        $item = ExpenseTransaction::whereIn('branch_id',$branchId )->where('expense_group', 2)->where('id', $id)->whereNull('deleted_on')->first();
        $item->expense_date = date("m/d/Y",strtotime($item->expense_date));
        if (empty($item)) {
            return redirect()->route('expense.cash_transaction_list');
        }
        return view('expense.edit_cash_transaction',[
            'branches' => $listBranch,
            'item' => $item,
            'currency' => config('role.currency_list')
        ]);

    }
    public function updateCashTransaction(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);
        $item = ExpenseTransaction::whereIn('branch_id',$branchId )->where('expense_group', 2)->where('id', $id)->whereNull('deleted_on')->first();
        if (empty($item)) {
            return redirect()->route('expense.cash_transaction_list');
        }
        $param = [
            'type' => $request->input('type'),
            'expense_date' =>date('Y-m-d', strtotime( $request->input('expense_date'))),
            'branch_id' => $request->input('branch_id'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'expense_group' => 2

        ];
        $item->update($param);
        return back();
    }
    public function deleteCashTransaction(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);
        $item = ExpenseTransaction::whereIn('branch_id',$branchId )->where('expense_group', 2)->where('id', $id)->whereNull('deleted_on')->first();
        if (empty($item)) {
            return redirect()->route('expense.cash_transaction_list');
        }
        $item->update([
            'deleted_on' => date("Y-m-d"),
            'updated_by' => $user->id
        ]);
        return back();

    }
    public function bankTransactionList(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);

        $data = ExpenseTransaction::whereIn('branch_id',$branchId )->where('expense_group', 1)->whereNull('deleted_on')->paginate(10);
        return view('expense.bank_transaction_list',['data' => $data]);
    }
    public function addBankTransaction(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);
        $accounts =  BankAccount::whereIn('branch_id',$branchId)->get();
        return view('expense.add_bank_transaction',[
            'branches' => $listBranch,
            'accounts' => $accounts
        ]);

    }
    public function saveBankTransaction(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }


        $param = [
            'type' =>  $request->input('type'),
            'expense_date' =>date('Y-m-d', strtotime( $request->input('expense_date'))),
            'branch_id' => $request->input('branch_id'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'customer_name' => $request->input('customer_name'),
            'bank_account_id' => $request->input('bank_account_id'),
            'transaction_code' => $request->input('transaction_code'),
            'rate' => $request->input('rate'),
            'expense_group' => 1,
            'created_by' => $user->id


        ];
        ExpenseTransaction::create($param);
        return back();

    }
    public function editBankTransaction(Request $request, $id)
    {

        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);
        $accounts =  BankAccount::whereIn('branch_id',$branchId)->get();
        $item =  ExpenseTransaction::whereIn('branch_id',$branchId)->where('expense_group', 1)->whereNull('deleted_on')->where('id', $id)->first();
        if (empty($item)) {
            return redirect()->route('expense.bank_transaction_list');
        }
        return view('expense.edit_bank_transaction',[
            'branches' => $listBranch,
            'accounts' => $accounts,
            'item' => $item
        ]);



    }
    public function reportBankTransaction(Request $request)
    {

        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);
        $accounts =  BankAccount::whereIn('branch_id',$branchId)->get();

        return view('expense.export_bank_transaction',[
            'branches' => $listBranch,
            'accounts' => $accounts,
        ]);



    }
    public function exportBankTransaction(Request $request)
    {

        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);
        $account =  BankAccount::whereIn('branch_id',$branchId)->where('id', $request->input('bank_account_id'))->first();
        $startDate = $request->input('start_date', '');
        $endDate = $request->input('end_date', '');
        if (!empty($startDate)) {
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));
        }
        $param = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'balance' => $request->input("balance")
        ];
        $path = WorkflowExport::exportBankTransaction($account, $param);

        //   dd($jobs->toArray());
        $filename = "cash_at_".$account->code.'_'.$account->currency. "_".date("ymd").'.xlsx';
        return response()->download($path, $filename, []);



    }
    public function updateBankTransaction(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);
        $item = ExpenseTransaction::whereIn('branch_id',$branchId )->where('expense_group', 4)->where('id', $id)->whereNull('deleted_on')->first();
        if (empty($item)) {
            return redirect()->route('expense.visa_paid_list');
        }
        $param = [
            'expense_date' =>date('Y-m-d', strtotime( $request->input('expense_date'))),
            'branch_id' => $request->input('branch_id'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'rate' => $request->input('rate'),


        ];
        $item->update($param);
        return back();
    }
    public function deleteBankTransaction(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);
        $item = ExpenseTransaction::whereIn('branch_id',$branchId )->where('expense_group', 4)->where('id', $id)->whereNull('deleted_on')->first();
        if (empty($item)) {
            return redirect()->route('expense.visa_paid_list');
        }
        $item->update([
            'deleted_on' => date("Y-m-d"),
            'updated_by' => $user->id
        ]);
        return back();

    }
    public function visaPaidList(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);

        $data = ExpenseTransaction::whereIn('branch_id',$branchId )->where('expense_group', 4)->whereNull('deleted_on')->paginate(10);
        return view('expense.visa_paid_list',['data' => $data]);
    }
    public function addVisaPaid(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();

        return view('expense.add_visa_paid',[
            'branches' => $listBranch
        ]);

    }
    public function saveVisaPaid(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $param = [
            'type' => 'debited',
            'expense_date' =>date('Y-m-d', strtotime( $request->input('expense_date'))),
            'branch_id' => $request->input('branch_id'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'expense_group' => 4,
            'currency' => 'VND',
            'created_by' => $user->id


        ];
        ExpenseTransaction::create($param);
        return back();

    }
    public function editVisaPaid(Request $request, $id)
    {

        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);
        $item = ExpenseTransaction::whereIn('branch_id',$branchId )->where('expense_group', 4)->where('id', $id)->whereNull('deleted_on')->first();
        $item->expense_date = date("m/d/Y",strtotime($item->expense_date));
        if (empty($item)) {
            return redirect()->route('expense.visa_paid_list');
        }
        return view('expense.edit_visa_paid',[
            'branches' => $listBranch,
            'item' => $item
        ]);

    }
    public function updateVisaPaid(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);
        $item = ExpenseTransaction::whereIn('branch_id',$branchId )->where('expense_group', 4)->where('id', $id)->whereNull('deleted_on')->first();
        if (empty($item)) {
            return redirect()->route('expense.visa_paid_list');
        }
        $param = [
            'expense_date' =>date('Y-m-d', strtotime( $request->input('expense_date'))),
            'branch_id' => $request->input('branch_id'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),

        ];
        $item->update($param);
        return back();
    }
    public function deleteVisaPaid(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);
        $item = ExpenseTransaction::whereIn('branch_id',$branchId )->where('expense_group', 4)->where('id', $id)->whereNull('deleted_on')->first();
        if (empty($item)) {
            return redirect()->route('expense.visa_paid_list');
        }
        $item->update([
            'deleted_on' => date("Y-m-d"),
            'updated_by' => $user->id
        ]);
        return back();

    }
    public function expenseNrpList(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);

        $data = ExpenseTransaction::whereIn('branch_id',$branchId )->where('expense_group', 3)->whereNull('deleted_on')->paginate(10);
        return view('expense.expense_nrp_list',['data' => $data]);
    }
    public function addExpenseNrp(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();

        return view('expense.add_expense_nrp',[
            'branches' => $listBranch
        ]);

    }
    public function saveExpenseNrp(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $param = [
            'type' => 'debited',
            'expense_date' =>date('Y-m-d', strtotime( $request->input('expense_date'))),
            'branch_id' => $request->input('branch_id'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'expense_group' => 3,
            'currency' => 'VND',
            'created_by' => $user->id


        ];
        ExpenseTransaction::create($param);
        return back();

    }
    public function editExpenseNrp(Request $request, $id)
    {

        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);
        $item = ExpenseTransaction::whereIn('branch_id',$branchId )->where('expense_group', 3)->where('id', $id)->whereNull('deleted_on')->first();
        $item->expense_date = date("m/d/Y",strtotime($item->expense_date));
        if (empty($item)) {
            return redirect()->route('expense.expense_nrp_list');
        }
        return view('expense.edit_expense_nrp',[
            'branches' => $listBranch,
            'item' => $item
        ]);

    }
    public function updateExpenseNrp(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);
        $item = ExpenseTransaction::whereIn('branch_id',$branchId )->where('expense_group', 3)->where('id', $id)->whereNull('deleted_on')->first();
        if (empty($item)) {
            return redirect()->route('expense.expense_nrp_list');
        }
        $param = [
            'expense_date' =>date('Y-m-d', strtotime( $request->input('expense_date'))),
            'branch_id' => $request->input('branch_id'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),

        ];
        $item->update($param);
        return back();
    }
    public function deleteExpenseNrp(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','expense_summary',  'expense_branch'])) {
            return back(401);
        }
        $listBranch = $this->getValidBranch();
        $branchId = array_keys($listBranch);
        $item = ExpenseTransaction::whereIn('branch_id',$branchId )->where('expense_group', 3)->where('id', $id)->whereNull('deleted_on')->first();
        if (empty($item)) {
            return redirect()->route('expense.expense_nrp_list');
        }
        $item->update([
            'deleted_on' => date("Y-m-d"),
            'updated_by' => $user->id
        ]);
        return back();

    }



    private function getValidBranch(){
        $user = Auth::user();
        $roles = Auth::user()->roles();
        $listBranch = [];

        if($user->hasRole('admin')){
            $companies = Company::all();
            foreach ($companies as $company) {

                $cBranches = $company->branches;

                foreach ($cBranches as $branch){
                    $listBranch[$branch->id] = $branch;
                }
            }


        } else {

            foreach ($roles->get() as $role) {
                if($role->group != 'expense_summary' || $role->group != 'expense_branch') {
                    continue;
                }
                $company = $role->company()->first();
                if($role->group == 'expense_summary'){
                    $cBranches = $company->branches;
                    foreach ($cBranches as $branch){
                        $listBranch[$branch->id] = $branch;
                    }
                }
                if($role->group == 'expense_branch'){
                    $cBranches = $role->branch;
                    $listBranch[$cBranches->id] = $cBranches;
                }

            }
        }
        return $listBranch;
    }


}
