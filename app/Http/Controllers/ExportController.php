<?php

namespace App\Http\Controllers;

use App\Facades\Summary;
use App\Facades\Workflow;
use App\Facades\WorkflowExport;
use App\Helper\Common;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerAliasName;
use App\Models\InvoiceImport;
use App\Models\InvoiceType;
use App\Models\Job;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $list =[["AI" => 120,'Cond' => "a", "Check" => 'a'], ["AI" => 270,'Cond' => "a", "Check" => 'a']];
        $config = config('import');
        $result = Summary::calculateColumnG($list,$config);
        dd($result);
        //WorkflowExport::exportSample();
    }



    public function customerSummaryWorking(Request $request){
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','summary_work'])) {
            return back(401);
        }
        $roles = Auth::user()->roles();
        if($user->hasRole('admin')){
            $companies = Company::all();

        } else {
            $companies = [];
            foreach ($roles->get() as $role) {
                if($role->group != 'summary_work') {
                    continue;
                }
                $company = $role->company()->first();
                $companies[$company->id] = $company;
            }
        }


        $endDate = date("m/d/Y");
        $startDate = new \DateTime($endDate);
        $startDate = $startDate->modify('-1 month')->format('m/d/Y');

        return view('summary_work.customer_summary',[
            'companies' => $companies,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);


    }

    public function teamSummaryWorking(Request $request){

        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','summary_work'])) {
            return back(401);
        }
        $listTeam = [];
        $roles = Auth::user()->roles();
        if($user->hasRole('admin')){
            $companies = Company::all();
        } else {
            $companies = [];
            foreach ($roles->get() as $role) {
                if($role->group != 'summary_work') {
                    continue;
                }
                $company = $role->company()->first();
                $companies[$company->id] = $company;
            }
        }

        $endDate = date("m/d/Y");
        $startDate = new \DateTime($endDate);
        $startDate = $startDate->modify('-1 month')->format('m/d/Y');

        return view('summary_work.team_summary',[
            'companies' => $companies,
            'teams' => $listTeam,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);


    }
    public function exportTeamSummaryWorking(Request $request){
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','summary_work'])) {
            return back(401);
        }


        $teamId = $request->input('team_id');
        $team = Team::find($teamId)->toArray();
        $param = [
            'company' => Company::find($request->input('company_id'))->toArray(),
            'team' => $team
        ];
        //    $name = '株式会社山西(構造CAD)構造CAD';
        $startDate = $request->input('start_date', '');
        $endDate = $request->input('end_date', '');
        if (!empty($startDate)) {
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));
        }
        $jobs = Job::where([['team_id', $teamId], ['henkyaku_hi', '>=', $startDate], ['henkyaku_hi', '<=', $endDate]])->orderBy('hacchyuu_naiyou')->get();
        $path = WorkflowExport::exportTeamSummary($param, $jobs->toArray());

        //   dd($jobs->toArray());
        $filename = "summary_of_work_".$param['company']['code'].'_'.$team['code']. "_".date("ymd").'.xlsx';
        return response()->download($path, $filename, []);
    }

    public function exportCustomerSummaryWorking(Request $request){
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','summary_work'])) {
            return back(401);
        }


        $companyId  = $request->input('company_id');
        $param = [
            'company' => Company::find($companyId),
        ];
        //    $name = '株式会社山西(構造CAD)構造CAD';
        $startDate = $request->input('start_date', '');
        $endDate = $request->input('end_date', '');
        if (!empty($startDate)) {
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));
        }
        $jobs = Job::where([['company_id', $companyId], ['henkyaku_hi', '>=', $startDate], ['henkyaku_hi', '<=', $endDate]])->orderBy('hacchyuu_naiyou')->get();
        $path = WorkflowExport::exportCustomerSummary($param, $jobs->toArray());

        //   dd($jobs->toArray());
        $filename = "summary_of_work_synthetic_".$param['company']['code']. "_".date("ymd").'.xlsx';
        return response()->download($path, $filename, []);
    }

    public function customerInvoice(Request $request){
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','customer_invoice'])) {
            return back(401);
        }
        $listCustomer = [];
        $roles = Auth::user()->roles();
        if($user->hasRole('admin')){
            $companies = Company::all();
            $listCustomer = Customer::all();

        } else {
            $companies = [];
            foreach ($roles->get() as $role) {
                if($role->group != 'customer_invoice') {
                    continue;
                }
                $company = $role->company()->first();
                //  dd($company);
                $customers = $company->customers;
                foreach ($customers as $customer){
                    $listCustomer[$customer->id] = $customer;
                }

                $companies[$company->id] = $company;
            }
        }
        $types = InvoiceType::all();

        $endDate = date("m/d/Y");
        $startDate = new \DateTime($endDate);
        $startDate = $startDate->modify('-1 month')->format('m/d/Y');

        return view('customer_invoice.customer_invoice',[
            'companies' => $companies,
            'customers' => $listCustomer,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'types' =>$types
        ]);


    }
    public function exportCustomerInvoice(Request $request){
        $user = Auth::user();
        if (!$user->hasAnyPermission(['admin','customer_invoice'])) {
            return back(401);
        }
        $listCustomer = [];
        $roles = Auth::user()->roles();
        if($user->hasRole('admin')){
            $companies = Company::all();
            $listCustomer = Customer::where('is_merchant', false)->get();

        } else {
            $companies = [];
            foreach ($roles->get() as $role) {
                if($role->group != 'customer_invoice') {
                    continue;
                }
                $company = $role->company()->first();
                //  dd($company);
                $customers = $company->customers;
                foreach ($customers as $customer){
                    if($customer->is_merchant){
                        continue;
                    }
                    $listCustomer[$customer->id] = $customer;
                }

                $companies[$company->id] = $company;
            }
        }
      //  dd($request->all());
        /*
  "company_id" => "2"
  "customer_id" => "2"
  "invoice_type" => "3"
  "start_date" => "03/02/
        "start_date" => "03/02/2021"
  "end_date" => "03/30/2021"
  "export_date" => "03/17/2021"
  "order_date" => "03/22/2021"
  "ship_date" => "03/18/2021"

         */
        $customer = Customer::find($request->input('customer_id'));
        if (empty($customer)){
            return back(404);
        }
        $merchant = Customer::find($customer->merchant_id);
        if (empty($merchant)){
            $merchant = $customer;
        }
        $invoiceNumber = 0;
        if(empty($customer['invoice_year']) || $customer['invoice_year'] < date("Y") ) {
            $customer->update(['invoice_year' =>date("Y"), 'invoice_number' => 1 ]);
            $invoiceNumber = 1;
        }else {
            $invoiceNumber = $customer['invoice_number'] + 1;
            $customer->update(['invoice_number' => $invoiceNumber ]);
        }

        $param = [
            'company' => Company::find($request->input('company_id'))->toArray(),
            'invoice_number' => "$invoiceNumber-".date("Y"),
            'customer' => $customer->toArray(),
            'merchant' => $merchant->toArray(),
            'invoice' => InvoiceType::find($request->input('invoice_type'))->toArray(),
            "export_date" => date('Y/m/d', strtotime($request->input('export_date'))),
            "order_date" => date('Y/m/d', strtotime($request->input('order_date'))),
            "ship_date" => date('Y/m/d', strtotime($request->input('ship_date')))
        ];
        $name = $param['customer']['name'];
    //    $name = '株式会社山西(構造CAD)構造CAD';
        $startDate = $request->input('start_date', '');
        $endDate = $request->input('end_date', '');
        if (!empty($startDate)) {
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));
        }
        $type = $param['invoice']['code'];
        if($type == 'money'){
            $name= $param['customer']['name'];
            $jobs = InvoiceImport::where([['customer_name', $name], ['created_at', '>=', $startDate],  ['created_at', '<=', $endDate]])->orderBy('item')->get();
        } else {

            $alias = CustomerAliasName::where('customer_id',$request->input('customer_id') )->get()->toArray();
            $listName = array_column($alias, 'alias_name');

            $jobs = Job::whereIn('tokuisakimei',$listName)-> where([['henkyaku_hi', '>=', $startDate], ['henkyaku_hi', '<=', $endDate]])->orderBy('hacchyuu_naiyou')->get();
        }



        $path = WorkflowExport::exportCustomerInvoice($param, $jobs->toArray());

     //   dd($jobs->toArray());
        $filename = "invoice_".$param['company']['name'].'_'.$param['invoice']['name'].'_'.date("ymd").'.xlsx';
        return response()->download($path, $filename, []);
    }


    public function getInvoiceData(Request $request) {
        $type =  $request->input('type');
        $id = $request->input('id');
        $result = [];
        if($type == 'get_customer') {
            $company = Company::find($id);
            $customers = $company->customers;
            foreach ($customers as $customer) {
                $result[] = [
                    'id' => $customer->id,
                    'name' => $customer->name
                ];
            }
        }
        if($type == 'get_team') {
            $company = Company::find($id);
            $teams = $company->teams;
            foreach ($teams as $team) {
                $result[] = [
                    'id' => $team->id,
                    'name' => $team->name
                ];
            }
        }

        if($type == 'get_invoice_type') {
            $customer = Customer::find($id);
            $types = $customer->invoiceType;
            foreach ($types as $type) {
                $result[] = [
                    'id' => $type->id,
                    'name' => $type->name
                ];
            }
        }

        return json_encode($result);
    }
}
