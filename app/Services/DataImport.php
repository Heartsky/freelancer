<?php
namespace App\Services;


use App\Helper\Common;
use App\Models\Company;
use App\Models\CompanyCustomer;
use App\Models\Customer;
use App\Models\CustomerAliasName;
use App\Models\CustomerJobCategory;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\JobMaster;
use App\Models\Staff;
use App\Models\Team;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DataImport  {

    private $config;
    public function __construct() {
        $this->config = config('import');
    }


    public function importData($fileExcel, $param)
    {

        return $this->readDataFromExcel($fileExcel, $param);
    }

    public function readDataFromExcel($fileExcel, $param) {
        $dateField = [
            'kenzai', 'uketsuke_hi','kibou_nouki','teisei_henkyaku_hi','henkyaku_hi',
            'last_update', 'complete_time'
        ];

        try {

            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xls");
            $spreadsheet = $reader->load($fileExcel);

            $sheet = $spreadsheet->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $header = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1,
                NULL,
                TRUE,
                FALSE);
            $position = $this->getPositonConlum($header[0]);

            for ($row = 2; $row <= $highestRow; $row++){
                //  Read a row of data into an array
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                NULL,
                                                TRUE,
                                                FALSE)[0];
                $item = [
                    'company_id' => $param['company'],
                    'team_id' => $param['team']
                ];
                foreach ($position as $key=> $value) {
                    if(!is_null($value)) {

                        $val = @$rowData[$value];
                        if(in_array($key, $dateField) && !empty($val)) {
                            $val = Common::formatDate($val);
                        }
                        $item[$key] = trim($val);
                    }
                }

                Job::updateOrCreate(['internal_id' => $item['internal_id']], $item);


            }
            return ['status' => 'success', 'msg' => 'Upload successfully'];
        } catch(Exception $e) {
            return ['status' => 'danger', 'msg' => $e->getMessage()];
        }
    }



    private function getPositonConlum($header) {
        $headerFlip = array_flip($header);
       //    dd($headerFlip);
        $result = $this->config;
        foreach ($result as $key => &$value) {
            $value = @$headerFlip[trim($value)];
        }
        return $result;
    }

    public function importTeam($companyId){

        $company = Company::find($companyId);

        $path = storage_path("app/init_data/team_{$company->code}.xlsx");


        if(!file_exists($path)){
            return;
        }
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load($path);

        $sheet = $spreadsheet->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $header = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1,
            NULL,
            TRUE,
            FALSE);

        $list = [];

        for ($row = 2; $row <= $highestRow; $row++) {

            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                NULL,
                TRUE,
                FALSE)[0];
            //  dd($rowData);
            try {
                $team = [
                    'code' => strtoupper($rowData[1]),
                    'name'=>  $rowData[0],
                    'company_id' => $companyId,

                ];

                Team::updateOrCreate(['code'=> $team['code']],$team);
            } catch (\Exception $ex) {
                dd($ex);
            }


        }

        return [ 'status' => 'success',
            'msg' => 'Import data successfully!, Please back to list staff management'];

    }

    public function importStaff($companyId, $path = '', $isUpload = false){
        $team  = Team::select("id", "code")->get();
        $teams = [];
        foreach ($team as $key => $value) {
            $teams[$value->code] = $value->id;
        }
        $company = Company::find($companyId);
        if(!$isUpload){
            $path = storage_path("app/init_data/user_{$company->code}.xlsx");
        }

        if(!file_exists($path)){
            return;
        }
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load($path);

        $sheet = $spreadsheet->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $header = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1,
            NULL,
            TRUE,
            FALSE);

        $list = [];

        for ($row = 2; $row <= $highestRow; $row++) {

            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                NULL,
                TRUE,
                FALSE)[0];
          //  dd($rowData);
            try {
                $staff = [
                    'code' => $rowData[0],
                    'staff_id' => $rowData[1],
                    'area'=> $rowData[2],
                    'name'=>  $rowData[3],
                    'position' => $rowData[4],
                    'company_id' => $companyId,
                    'team_id' => @$teams[@$rowData[5]]
                ];
                Staff::updateOrCreate(['code'=> $staff['code']],$staff);
            } catch (\Exception $ex) {
                dd($ex);
            }


        }

        return [ 'status' => 'success',
                 'msg' => 'Import data successfully!, Please back to list staff management'];

    }

    public function importJob($companyId){
        $company = Company::find($companyId);
        $path = storage_path("app/init_data/job_master_{$company->code}.xlsx");
        if(!file_exists($path)){
            return;
        }
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load($path);

        $sheet = $spreadsheet->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $header = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1,
            NULL,
            TRUE,
            FALSE);
        $list = [];
        $index = 1;
        $order = 1;
        for ($row = 2; $row <= $highestRow; $row++) {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                NULL,
                TRUE,
                FALSE)[0];

            $jm = [
                'job_master_name' => trim($rowData[1]),
                'summary_type' => trim($rowData[7]),
                'summary_group' => trim($rowData[6]),
                'is_rank' => false,
                'company_id' => $companyId,
                'rank_code' => ""
            ];
            if(!empty(trim($rowData[3]))){
                $jm['is_rank'] = true;
                $jm['rank_code'] = trim($rowData[3]);

            }
            $jc = [];
            if(!empty($rowData[2])){
                $jc['job_category_name'] = trim($rowData[2]);
                $jc['price_count'] = trim($rowData[4]);
                $jc['price_sqm'] = trim($rowData[5]);
                $jc['summary_order'] = $order;
            }
            if(!isset($list[trim($rowData[1])])){
                $jm['summary_order'] = $index;
                $item = [
                    'jm' => $jm,
                    'jc'=> [
                        trim($rowData[2]) => $jc
                    ]
                ];
                $index ++;
            } else {
                $item = $list[trim($rowData[1])];
                $item['jc'][trim($rowData[2])] = $jc;
            }
            $list[trim($rowData[1])] = $item;
        }

        $this->saveJob($list);
    }

    private function saveJob($list) {
        foreach ($list as $item) {

            $master = new JobMaster($item['jm']);
            $master->save();
            $category = [];
            foreach ($item['jc'] as $i) {
                if(!empty($i)) {
                    $category [] = new JobCategory($i);
                }

            }
            if(count($category)) {
                $master->jobCategoies()->saveMany($category);
            }

        }
    }

    public function importCustomer($companyId){

        $company = Company::find($companyId);

        $path = storage_path("app/init_data/cus_{$company->code}.xlsx");
        if(!file_exists($path)){
            return;
        }
        $listMaster = [];
        $jobMasters = JobMaster::all();
        foreach ($jobMasters as $job) {
            $listMaster[$job->job_master_name] = $job->id;
        }
        $listCat = [];
        $jobCates = JobCategory::all();

        foreach ($jobCates as $job) {
            $listCat[$job->job_category_name] = $job->id;
        }

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load($path);

        $sheet = $spreadsheet->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $header = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1,
            NULL,
            TRUE,
            FALSE);

        $list = [];

        for ($row = 2; $row <= $highestRow; $row++) {

            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                NULL,
                TRUE,
                FALSE)[0];
         //   dd($header,$rowData);
            try {
                $customer = [
                    'code' => $rowData[1],
                    'name' => $rowData[1]
                ];


                $c = Customer::updateOrCreate(['name'=> $customer['name']],$customer);


                $companyCustomer = [
                    'customer_id' => $c->id,
                    'company_id' =>  $company->id
                ];
                CompanyCustomer::updateOrCreate(['customer_id' => $companyCustomer['customer_id'], 'company_id' => $companyCustomer['company_id']],$companyCustomer);

                $customerAlias = [
                    'customer_id' => $c->id,
                    'alias_name' =>  $rowData[2],
                    'department' => $rowData[0]
                ];
                CustomerAliasName::updateOrCreate(['alias_name'=> $customerAlias['alias_name']],$customerAlias);
                $customerJob = [
                    'customer_id' => $c->id,
                    'job_master_id' => @$listMaster[$rowData[3]],
                    'job_category_id' => @$listCat[$rowData[4]],
                    'customer_alias' => @$listCat[$rowData[2]],
                    'point' => $rowData[6],
                    'point_check' => $rowData[7],
                    'company_id' => $companyId
                ];
                CustomerJobCategory::updateOrCreate([
                    'customer_id'=> $customerJob['customer_id'],
                    'job_master_id'=> $customerJob['job_master_id'],
                    'job_category_id'=> $customerJob['job_category_id'],

                ],$customerJob);
            } catch (\Exception $ex) {
                dd($listMaster,$listCat, $rowData,$ex->getMessage());
                dd($ex->getMessage());
            }
          //  dd(1);

        }
        return [ 'status' => 'success',
            'msg' => 'Import data successfully!, Please back to list staff management'];

    }




}
