<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Customer;
use App\Models\InvoiceType;
use App\Models\MasterJobRank;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use App\Models\JobMaster;
use App\Models\JobCategory;
use App\Services\DataImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\CompanyCustomer;
use App\Models\CustomerAliasName;
use App\Models\Staff;
use App\Models\CustomerJobCategory;
use App\Models\Branch;

class MasterManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Admin,web');
    }
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */

    public function show()
    {
        return view('profile.show');
    }

    public function edit()
    {
        return view('profile.edit');
    }


    public function companyList(Request $request) {
        /*
        $sv = app(DataImport::class);
        $sv->importCustomer(1);
        dd(3);
        */
        $data = Company::paginate(10);
       // dd($data->count());
        return view('company.company_list',['data' => $data]);
    }

    public function companyCreate(Request $request){

        if($request->getMethod() == 'GET') {
            return view('company.company_add',['company' => new Company()]);
        }

        if($request->getMethod() == 'POST') {
            $dataCus = $request->all();
            $company = Company::create($dataCus);
            $roles = [];
            $listRole = config('role.list_role');
            foreach($listRole as $item)
            {
                $role = new Role();
                $role->name = $item.'_'.$company->code;
                $role->description =  $company->name. ' '. __('role.'.$item);
                $role->group = $item;
                $roles[] = $role;
            }

            $company->roles()->saveMany($roles);
            return redirect()->route('master.company_detail',['id'=> $company->id]);
        }
    }

    public function companyDetail(Request $req, $id)
    {
        $company = Company::find($id);
    //    $summary=['teams' => $company->teams->count(),'customers'=>  $company->customers->count(),'staffs'=>  $company->staffs->count()];
        $summary=['teams' => 0,'customers'=>  0,'staffs'=>  0];

        $bankAccount = $company->bankAccount;
       // dd($company->teams()->count());

        return view('company.company_detail',[
            'company' => $company,
            'bankAccount' => $bankAccount,
            'summary' => $summary
            ]);
    }

    public function companyUpdate(Request $req, $id)
    {

        $company = Company::find($id);
        $company->update($req->all());
        return redirect()->route('master.company_detail',['id'=> $company->id]);
    }
    public function companyDelete(Request $req, $id)
    {
        $company = Company::find($id);
        $company->delete();
        return redirect()->route('master.company_management');
    }

    public function teamList(Request $request) {

        $data = Team::paginate(10);
        // dd($data->count());
        return view('team.team_list',['data' => $data]);
    }

    public function teamCreate(Request $request){
        $companies  = Company::select("id", "name")->get();

        if($request->getMethod() == 'GET') {
            return view('team.team_add',['team' => new Team(), 'companies' => $companies]);
        }

        if($request->getMethod() == 'POST') {
            $team = Team::create($request->all());

            return redirect()->route('master.team_detail',['id'=> $team->id]);
        }
    }

    public function teamDetail(Request $req, $id)
    {
        $team = Team::find($id);
        $companies  = Company::select("id", "name")->get();

        // dd($company->teams()->count());
        return view('team.team_detail',['team' => $team, 'companies'=> $companies]);
    }

    public function teamUpdate(Request $req, $id)
    {

        $team = Team::find($id);
        $team->update($req->all());
        return redirect()->route('master.team_detail',['id'=> $team->id]);
    }
    public function teamDelete(Request $req, $id)
    {
        $team = Team::find($id);
        $team->delete();
        return redirect()->route('master.team_management');
    }



    public function userList()
    {
        $data = User::paginate(10);
        return view('users.user_list',['data' => $data]);
    }

    public function userDetail(Request $request, $id)
    {

        $user = User::find($id);
        $permission = $user->hasRole('Admin');
        if($permission) {
            $roles = Role::where('name', "<>", null)->get();
        } else {
            $roles = Role::where('name', "<>", "Admin")->get();
        }
        $userRole = $user->roles->toArray();
       // dd(array_column($userRole,'id'));
        return view('users.user_detail',['user' => $user, 'roles' => $roles, 'currentRole' => array_column($userRole,'id')]);
    }

    public function userCreate(Request $request)
    {

        $user = new User();
        $permission = $user->hasRole('Admin');
        if($permission) {
            $roles = Role::where('name', "<>", null)->get();
        } else {
            $roles = Role::where('name', "<>", "Admin")->get();
        }
        if($request->getMethod() == 'GET') {
            return view('users.user_add',['user' => $user, 'roles' => $roles]);
        }

        if($request->getMethod() == 'POST') {

            $employee = new User();
            $employee->name = $request->input('name');
            $employee->email = $request->input('email');
            $employee->password = bcrypt('123456');
            $employee->save();
            $userRoles = $request->input('roles');
            foreach ($userRoles as $role){
                $userRole = Role::find($role);
                if(!empty($userRole)) {
                    $employee->roles()->attach($userRole);
                }
            }
            return redirect()->route('master.user_management');
        }

    }


    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userUpdate(Request $request, $id)
    {

        $employee = User::find($id);
        $employee->name = $request->input('name');
        $employee->email = $request->input('email');

        $employee->save();
        $userRoles = $request->input('roles');
        $employee->roles()->sync($userRoles);
        return redirect()->route('master.user_detail', ['id' => $employee->id]);
    }

    public function userDelete(Request $req, $id) {
        User::find($id)->delete();
        return redirect()->route('master.user_management');
    }

    public function customerManagement(Request $request)
    {
        $keyword = $request->get('keyword', '');
        if(trim($keyword) != ""){
            $data = Customer::where('name', 'like', '%'.$keyword.'%')->paginate(10);
        }else{
            $data = Customer::OrderBy("id", 'desc')->paginate(10);
        }


       // dd($data->toArray());
        return view('customer.customer_list',['data' => $data, 'keyword' => $keyword]);

    }

    public function customerCreate(Request $request){

        if($request->getMethod() == 'GET') {
            $company = Company::all();
            $types = InvoiceType::all();
            return view('customer.customer_add',compact('company', 'types'));
        }

        if($request->getMethod() == 'POST') {
            $dataAdd = $request->all();

            $customer = [
                "code" => $dataAdd["code"],
                "name" => $dataAdd["name"],
                "address" => $dataAdd["address"],
                "price_tsubo" => $dataAdd["price_tsubo"],
                "price_acreage" => $dataAdd["price_acreage"],
                "price_item" => $dataAdd["price_item"],
                "phone_number" => $dataAdd["phone_number"],
                "type_invoice" => $dataAdd["type_invoice"],
                "summary_rank" => $dataAdd["summary_rank"],
                "fax" => $dataAdd["fax"],
                'is_merchant' => !empty($dataAdd['is_merchant']) ? 1 : 0
            ];
            $cus = Customer::create($customer);

            // add company customer
            foreach ($dataAdd['company_ids'] as $value) {
                CompanyCustomer::create(['customer_id' => $cus->id, 'company_id' => $value]);
            }

            return redirect()->route('master.customer_management');
        }
    }

    public function customerDetail(Request $req, $id)
    {
        $customer  = Customer::find($id);
        $bankAccount = $customer->bankAccount;

        if($req->getMethod() == 'POST') {
            $customer = Customer::find($id);
            $dataAdd = $req->all();
            $customerData = [
                "code" => $dataAdd["code"],
                "name" => $dataAdd["name"],
                "address" => $dataAdd["address"],
                "phone_number" => $dataAdd["phone_number"],
                "price_tsubo" => $dataAdd["price_tsubo"],
                "price_acreage" => $dataAdd["price_acreage"],
                "price_item" => $dataAdd["price_item"],
                "type_invoice" => $dataAdd["type_invoice"],
                "summary_rank" => $dataAdd["summary_rank"],
                "fax" => $dataAdd["fax"],
                'is_merchant' => !empty($dataAdd['is_merchant']) ? 1 : 0
            ];
            $customer->update($customerData);

            // add company customer
            CompanyCustomer::where('customer_id', $id)->delete();
            foreach ($dataAdd['company_ids'] as $com) {
                CompanyCustomer::create(['customer_id' => $id, 'company_id' => $com]);
            }

            return redirect()->route('master.customer_management');
        }

        $customerCompany = CompanyCustomer::where('customer_id', $id)->get();
        $customerCompanyArr = [];
        foreach ($customerCompany as $value) {
            $customerCompanyArr[] = $value->company_id;
        }

        $companyAll = Company::all();
        $types = InvoiceType::all();

        return view('customer.customer_detail',compact('customer', 'bankAccount', 'customerCompanyArr', 'companyAll', 'types'));
    }

    public function customerJob(Request $request, $masterId)
    {
        if($request->getMethod() == 'POST') {
            $dataRq = $request->all();
            if(!empty($dataRq['cat_id'])){
                $cat = JobCategory::find($dataRq['cat_id']);
                if(!empty($cat)){
                    $cat->job_master_id = $dataRq['job_master_id'];
                    $cat->summary_order = $dataRq['summary_order'];
                    $cat->job_category_name = $dataRq['job_category_name'];
                    $cat->price_count = $dataRq['price_count'];
                    $cat->price_sqm = $dataRq['price_sqm'];
                    $cat->save();
                }
            }elseif(!empty($dataRq['save_position'])){
                foreach ($dataRq['summary_order'] as $id => $value) {
                    $job = JobCategory::find($id);
                    $job->position = $value[0];
                    $job->save();
                }
            }else{
                $dataRq['job_master_id'] = $masterId;
                JobCategory::create($dataRq);
            }
        }

        $id = $request->get('category_id');
        $catJob = '';
        if(!empty($id)){
            $catJob = JobCategory::find($id);
        }

        $data = JobCategory::where('job_master_id', $masterId)->orderBy('summary_order', 'asc')->get();
        $jobmaster = JobMaster::orderBy('summary_order', 'asc')->get();

        return view('company.job_category', compact('data', 'masterId', 'jobmaster', 'catJob'));
    }

    public function customerAliasName(Request $request, $customerId)
    {
        if($request->getMethod() == 'POST') {
            $dataRq = $request->all();
            if(!empty($dataRq['alias_id_edit'])){
                $cat = CustomerAliasName::find($dataRq['alias_id_edit']);
                if(!empty($cat)){
                    $cat->alias_name = $dataRq['alias_name'];
                    $cat->alias_code = $dataRq['alias_code'];
                    $cat->save();
                }
            }else{
                $dataRq['customer_id'] = $customerId;
                CustomerAliasName::create($dataRq);
            }
        }
        $data = CustomerAliasName::where('customer_id', $customerId)->get();
        $aliasName = '';
        $aliasId = $request->get('alias_id', 0);
        if(!empty($aliasId)){
            $aliasName = CustomerAliasName::find($aliasId);
        }

        return view('customer.alias_customer', compact('data', 'customerId', 'aliasName'));
    }

    public function customerAliasNameDelete(Request $request, $customerId, $id)
    {
        $job = CustomerAliasName::find($id);
        $job->delete();

        return redirect()->route('customer_alias_name', ['customerId' => $customerId]);
    }

    public function customerJobDelete(Request $request, $masterId, $id)
    {
        $job = JobCategory::find($id);
        $job->delete();

        return redirect()->route('customer_job_management', ['masterId' => $masterId]);
    }

    public function companyJob(Request $request, $companyId)
    {
        if($request->getMethod() == 'POST') {
            $dataAdd = $request->all();
            if(!empty($dataAdd['job_id'])){
                $job = JobMaster::find($dataAdd['job_id']);
                if(!empty($job)){
                    $job->is_enable = !empty($dataAdd['is_enable']) ? 1 : 0 ;
                    $job->is_rank = !empty($dataAdd['is_rank']) ? 1 : 0 ;
                    $job->job_master_name = $dataAdd['job_master_name'];
                    $job->summary_order = $dataAdd['summary_order'];
                    $job->summary_type = $dataAdd['summary_type'];
                    $job->summary_group = $dataAdd['summary_group'];
                    $job->rank_code = $dataAdd['rank_code'];
                    $job->save();
                }
            }elseif(!empty($dataAdd['save_position'])){
                foreach ($dataAdd['position'] as $id => $value) {
                    $job = JobMaster::find($id);
                    $job->position = $value[0];
                    $job->save();
                }
            }else{
                $dataAdd['company_id'] = $companyId;
                $dataAdd['is_rank'] = !empty($dataAdd['is_rank']) ? 1 : 0 ;
                $dataAdd['is_enable'] = !empty($dataAdd['is_enable']) ? 1 : 0 ;
                JobMaster::create($dataAdd);
            }

        }

        $id = $request->get('master_id', 0);
        $job = '';
        if(!empty($id)){
            $job = JobMaster::find($id);
        }

        $keyword = $request->get('keyword', '');

        $query = JobMaster::where('company_id', $companyId);

        if(trim($keyword) != ""){
            $query->where('job_master_name', 'like', '%'.$keyword.'%');
        }

        $data = $query->orderBy('summary_order', 'asc')->get();
        $summaryTypes = config('role.summary_type');
        $summaryGroup = config('role.summary_group');
        return view('company.job_company', compact('data', 'job', 'companyId', 'summaryTypes', 'summaryGroup', 'keyword'));
    }

    public function masterJobRank(Request $request, $masterId) {
        $data =  MasterJobRank::where('master_job_id', $masterId)->get();
        return view('company.job_rank_list', compact('data', 'masterId'));
    }

    public function createMasterJobRank(Request $request, $masterId) {

        return view('company.job_rank_create', compact( 'masterId'));
    }

    public function saveMasterJobRank(Request $request, $masterId) {
        $data = [
            'master_job_id' => $masterId,
            'rank_code' => $request->input('rank_code',''),
            'comment' => $request->input('comment', '')
        ];
        MasterJobRank::updateOrCreate(['master_job_id' => $masterId,
            'rank_code' => $request->input('rank_code','')], $data);

        return redirect()->route('master_job_rank_list', ['masterId' => $masterId]);
    }
    public function editMasterJobRank(Request $request, $masterId, $rankId) {
        $item = MasterJobRank::find($rankId);
        return view('company.job_rank_edit', compact( 'masterId', 'item'));
    }

    public function updateMasterJobRank(Request $request, $masterId, $rankId) {
        $item = MasterJobRank::find($rankId);
        $data = [
            'rank_code' => $request->input('rank_code',''),
            'comment' => $request->input('comment', '')
        ];
        $item->update($data);
        return redirect()->route('master_job_rank_list', ['masterId' => $masterId]);
    }

    public function deleteMasterJobRank(Request $request, $masterId, $rankId) {
        MasterJobRank::find($rankId)->delete();
        return redirect()->route('master_job_rank_list', ['masterId' => $masterId]);
    }


    public function companyJobDelete(Request $request, $companyId, $id)
    {
        $job = JobMaster::find($id);
        $job->delete();

        return redirect()->route('master_job_management', ['companyId' => $companyId]);
    }

    public function customerDelete(Request $req, $id)
    {
        $company = Customer::find($id);
        $company->delete();
        return redirect()->route('master.customer_management');
    }

    public function bankAccountList(Request $request) {

        $data = BankAccount::paginate(10);
        // dd($data->count());
        return view('bank_account.bank_account_list',['data' => $data]);
    }

    public function bankAccountCreate(Request $request){
        $companies  = Company::select("id", "name")->get();
        $customers  = Customer::select("id", "name")->get();
        $branches  = Branch::select("id", "name")->get();
        if($request->getMethod() == 'GET') {
            return view('bank_account.bank_account_add',['bankAccount' => new BankAccount(), 'companies' => $companies,'customers'=> $customers, 'branches' => $branches]);
        }

        if($request->getMethod() == 'POST') {
            $bankAccount = BankAccount::create($request->all());
            return redirect()->route('master.bank_account_detail',['id'=> $bankAccount->id]);
        }
    }

    public function bankAccountDetail(Request $req, $id)
    {
        $bankAccount = BankAccount::find($id);
        $companies  = Company::select("id", "name")->get();
        $customers  = Customer::select("id", "name")->get();
        $branches  = Branch::select("id", "name")->get();

        // dd($company->teams()->count());
        return view('bank_account.bank_account_detail',['bankAccount' => $bankAccount, 'companies'=> $companies, 'customers'=> $customers, 'branches' => $branches]);
    }

    public function bankAccountUpdate(Request $req, $id)
    {
        $bankAccount = BankAccount::find($id);
        $bankAccount->update($req->all());
        return redirect()->route('master.bank_account_detail',['id'=> $bankAccount->id]);
    }

    public function  companyUpdateBankAccount(Request $req, $id){
        $company = Company::find($id);
        $account = $company->bankAccount;
        if(empty($account)) {
            $account = BankAccount::create($req->all());
            $company->bankAccount()->save($account);
        } else {
            $account->update($req->all());
        }

        return redirect()->route('master.company_detail',['id'=> $company->id]);
    }

    public function  customerUpdateBankAccount(Request $req, $id){

        $cusomter = Customer::find($id);
        $account = $cusomter->bankAccount;
        #dd($account, $req->all());
        if(empty($account)) {
            $account = BankAccount::create($req->all());
            $cusomter->bankAccount()->save($account);
        } else {
            $account->update($req->all());
        }

        return redirect()->route('master.customer_detail',['id'=> $cusomter->id]);
    }


    public function bankAccountDelete(Request $req, $id)
    {
        $bankAccount = BankAccount::find($id);
        $bankAccount->delete();
        return redirect()->route('master.bank_account_management');
    }

    public function staffList(Request $req)
    {
        $data = Staff::OrderBy('id', 'desc')->paginate(20);

        return view('staff.staff_list',['data' => $data]);
    }

    public function staffCreate(Request $request)
    {
        $companies  = Company::select("id", "name")->get();
        $team  = Team::select("id", "name")->get();

        if($request->getMethod() == 'GET') {
            return view('staff.staff_add',['staff' => new Staff(), 'companies' => $companies,'teams'=> $team]);
        }

        if($request->getMethod() == 'POST') {
            $bankAccount = Staff::create($request->all());
            return redirect()->route('master.staff_management');
        }
    }

    public function staffDetail(Request $req, $id)
    {
        $staff = Staff::find($id);
        $companies  = Company::select("id", "name")->get();
        $teams  = Team::select("id", "name")->get();

        // dd($company->teams()->count());
        return view('staff.staff_detail',compact('staff', 'companies', 'teams'));
    }

    public function staffUpdate(Request $req, $id)
    {
        $staff = Staff::find($id);
        $staff->update($req->all());
        return redirect()->route('master.staff_management');
    }
    public function staffDelete(Request $req, $id){

        $staff = Staff::find($id);
        $staff->delete();
        return redirect()->route('master.staff_management');
    }


    public function staffImport(Request $request)
    {
        $extensionAccept = ['xlsx', 'xls']; $message = [];

        if($request->getMethod() == 'POST') {

            $extenstion = $request->file('input-data')->extension();
            if(in_array($extenstion, $extensionAccept)){

                $file = $request->file('input-data');
                $originalname = $file->getClientOriginalName();
                $path = $file->storeAs('public', $originalname);

                $inputFileName =storage_path("app/".$path);
                // import data
                $import = new DataImport();
                $message = $import->importStaff($request->get('company_id'), $inputFileName, true);

            } else {
                $message = [
                    'status' => 'danger',
                    'msg' => 'Only accept xlsx file!'
                ];
            }
        }

        $companies  = Company::select("id", "name")->get();

        return view('staff.staff_import', compact('companies', 'message'));
    }

    public function customerJobCategory(Request $request, $customerId)
    {
        if($request->getMethod() == 'POST') {
            $dataRq = $request->all();
            if(!empty($dataRq['cat_id'])){
                $cat = CustomerJobCategory::find($dataRq['cat_id']);
                if(!empty($cat)){
                    unset($dataRq['cat_id']);
                    unset($dataRq['job_master_id_edit']);
                    unset($dataRq['job_cat_id_edit']);

                    $cat->update($dataRq);
                }
            }else{
                $dataRq['customer_id'] = $customerId;
                CustomerJobCategory::create($dataRq);
            }
        }

        $id = $request->get('category_id');
        $catJob = '';
        if(!empty($id)){
            $catJob = CustomerJobCategory::find($id);
        }

        $data = CustomerJobCategory::where('customer_id', $customerId)->get();
        $companies  = Company::select("id", "name")->get();
        $jobmaster = JobMaster::orderBy('summary_order', 'asc')->get();

        return view('customer.job_category_customer', compact('data', 'customerId', 'jobmaster', 'catJob', 'companies'));
    }

    public function customerJobCategoryDelete(Request $request, $customerId, $id)
    {
        $job = CustomerJobCategory::find($id);
        $job->delete();

        return redirect()->route('customer_job_category', ['customerId' => $customerId]);
    }

    public function customerJobByCompany(Request $request)
    {
        $companyId = $request->get('company-id');
        $data = JobMaster::select('id', 'job_master_name')->where('company_id', $companyId)->get();

        return json_encode($data);
    }

    public function customerJobByMaster(Request $request)
    {
        $masterId = $request->get('master-id');
        $data = JobCategory::select('id', 'job_category_name')->where('job_master_id', $masterId)->get();

        return json_encode($data);
    }

    public function companyBranch(Request $request, $companyId)
    {
        if($request->getMethod() == 'POST') {
            $dataAdd = $request->all();
            if(!empty($dataAdd['branch_id_update'])){
                $job = Branch::find($dataAdd['branch_id_update']);
                if(!empty($job)){
                    $job->name = $dataAdd['name'];
                    $job->code = $dataAdd['code'];
                    $job->current_cash_balance = $dataAdd['current_cash_balance'];
                    $job->current_cycle = $dataAdd['current_cycle'];
                    $job->save();
                }
            }else{
                $dataAdd['company_id'] = $companyId;
                Branch::create($dataAdd);
            }

        }

        $id = $request->get('branch_id', 0);
        $job = '';
        if(!empty($id)){
            $job = Branch::find($id);
        }

        $keyword = $request->get('keyword', '');

        $query = Branch::where('company_id', $companyId);

        if(trim($keyword) != ""){
            $query->where('name', 'like', '%'.$keyword.'%');
        }

        $data = $query->get();

        return view('company.branch_company', compact('data', 'job', 'companyId', 'keyword'));
    }

    public function companyBranchDelete(Request $request, $companyId)
    {
        $id = $request->get('id');
        $job = Branch::find($id);
        $job->delete();

        return redirect()->route('master_branch_management', ['companyId' => $companyId]);
    }

    public function companyBranchById(Request $request)
    {
        $companyId = $request->get('company-id');
        $branch = Branch::where('company_id', $companyId)->get();

        return json_encode($branch);
    }

    public function customerMechant(Request $request)
    {
        $keyword = $request->get('keyword', '');
        if(trim($keyword) != ""){
            $data = Customer::where('name', 'like', '%'.$keyword.'%')->where('is_merchant', true)->paginate(10);
        }else{
            $data = Customer::where('is_merchant', true)->OrderBy('id', 'desc')->paginate(10);
        }


       // dd($data->toArray());
        return view('customer.customer_list_merchant',['data' => $data, 'keyword' => $keyword]);
    }

    public function customerMechantList(Request $request, $customerId)
    {

        if($request->getMethod() == 'POST'){
            $customerOfMerchant = Customer::find($request->get('customer_of_merchant_id'));
            $customerOfMerchant->merchant_id = $customerId;
            $customerOfMerchant->save();
        }

        $data = Customer::where('merchant_id', $customerId)->paginate(10);

        $customers = Customer::where('id', '<>', $customerId)->where('is_merchant', false)->where('merchant_id', null)->get();

        return view('customer.customer_list_merchant_list',['data' => $data, 'customerId' => $customerId, 'customers' => $customers]);
    }

    public function customerMechantListRemove(Request $request, $customerId)
    {
        $customer = Customer::find($customerId);
        $merchantId = $customer->merchant_id;
        $customer->merchant_id = null;
        $customer->save();

        return redirect()->route('customer_merchant_list', ['customerId' => $merchantId]);
    }

}
