<?php
namespace App\Services;


use App\Models\BankAccount;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\CustomerAliasName;
use App\Models\CustomerJobCategory;
use App\Models\ExpenseTransaction;
use App\Models\JobCategory;
use App\Models\JobMaster;
use App\Models\MasterJobRank;
use App\Models\Staff;
use Decimal\Decimal;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class WorkflowExport {

    function __construct()
    {

    }

    public function exportSample($name, $data) {
        $inputFileName =storage_path('app/templates/Invoice_ST_Tsubo.xlsx');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('C11', $name);
        $start = 22;
        $orderLine = 0;
        $price = 200;
        $totalTsubo = 0;
        $totalPrice = 0;
        foreach ($data as $job) {
            $start ++;
            $orderLine ++;
            $sheet->insertNewRowBefore($start);
            $sheet->setCellValue('B'.$start, $orderLine);
            $sheet->setCellValue('C'.$start, $job['hacchyuu_naiyou']);
            $sheet->setCellValue('D'.$start, $job['bukkenmei']);
            $sheet->setCellValue('E'.$start, $job['hacchyuumoto']);
            $sheet->setCellValue('F'.$start, number_format($job['tsubosuu']). '坪');
            $sheet->setCellValue('G'.$start, $price);
            $totalTsubo += $job['tsubosuu'];
            $priceLine = round($job['tsubosuu'] * $price,0, PHP_ROUND_HALF_UP);
            $totalPrice += $priceLine;
            $sheet->setCellValue('H'.$start,number_format($priceLine) . '円');

        }
        $sheet->getStyle('F22:F'.$start)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('H22:H'.$start)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $start++;
        $sheet->setCellValue('F'.$start, number_format($totalTsubo). '坪');
        $sheet->setCellValue('H'.$start,number_format($totalPrice) . '円');

        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/public/sample.xlsx');
        $writer->save($path);
        return $path;
    }

    public function exportCustomerInvoice($param, $data) {
        $type = $param['invoice']['code'];
        switch ($type) {
            case "acreage": return $this->exportTsubo($param, $data);
            case "count": return $this->exportCount($param, $data);
            case "money": return $this->exportMoney($param, $data);
        }

    }


    private function exportTsubo($param, $data){
        $inputFileName =storage_path('app/templates_invoice/'. $param['company']['code'].'/'.$param['invoice']['code'].'.xlsx');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
//        $spreadsheet->disableCalculationCache();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('H3', $param['export_date']);
        $sheet->setCellValue('H8', $param['order_date']);
        $sheet->setCellValue('H9', $param['ship_date']);

        $sheet->setCellValue('B11', $param['customer']['name']);
        $sheet->setCellValue('B12', $param['customer']['address']);
        $sheet->setCellValue('B13', $param['customer']['phone_number']);
        $sheet->setCellValue('B14', $param['customer']['fax']);

        $sheet->setCellValue('B16', $param['merchant']['name']);
        $sheet->setCellValue('B17', $param['merchant']['address']);
        $sheet->setCellValue('B18', $param['merchant']['phone_number']);
        $sheet->setCellValue('B19', $param['merchant']['fax']);

        $sheet->setCellValue('H4', $param['invoice_number']);

        $start = 22;
        $orderLine = 0;
        $totalTsubo = 0;
        $totalPrice = 0;
        $allprice = CustomerJobCategory::where('company_id', $param['company']['id'])->where('customer_id', $param['customer']['id'])->where('invoice_order', '>', 0)->get()->toArray();
        $listPrice = [];
        foreach ($allprice as $item){
            $listPrice[$item['job_category_id']] = floatval($item['price_count']);
        }
        $emptyStye =  array(
            'font' => array('bold' => false),
            'color' => ['argb' => 'FFFFFF'],
        );
//        $sheet->getStyle('A'.$start.':'.'H'.$start)
//            ->getAlignment()
//            ->setWrapText(true)
//            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
//            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        foreach ($data as $job) {
            $jobcategory = $job['hacchyuu_naiyou'];
            $currentCate = JobCategory::where('job_category_name', $jobcategory)->first();
            if(empty($currentCate)) {
                continue;
            }
            $currentCate = $currentCate->toArray();
            if(!isset($listPrice[$currentCate['id']])) {
                continue;
            }
            $price =  $listPrice[$currentCate['id']];
            if(empty($price)) {
                $price =  $currentCate['price_sqm'];
            }
            $start ++;
            $orderLine ++;

            $sheet->insertNewRowBefore($start,1);
            $sheet->getStyle('A'.$start.':'.'H'.$start)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffffff');
            $sheet->getStyle('A'.$start.':'.'H'.$start)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

            $sheet->getStyle('A'.$start.':'.'H'.$start)->applyFromArray($emptyStye);


            $sheet->setCellValue('A'.$start, $orderLine);
            $sheet->setCellValue('B'.$start, $job['hacchyuu_naiyou']);
            $sheet->setCellValue('C'.$start, $job['bukkenmei']);
            $sheet->setCellValue('D'.$start, $job['hacchyuumoto']);
            $sheet->setCellValue('E'.$start, $job['tsubosuu']. '坪');
            $sheet->setCellValue('F'.$start, $price);
            $totalTsubo += $job['tsubosuu'];
            $priceLine = $job['tsubosuu'] * $price;
            $priceLine = round($priceLine,0);
            $totalPrice += $priceLine;
            $sheet->setCellValue('G'.$start,$priceLine . '円');

        }

       # 02F9DE
        $sheet->getStyle('E23:E'.$start)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('G23:G'.$start)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $start++;
        $sheet->setCellValue('E'.$start, $totalTsubo . '坪');
        $sheet->setCellValue('G'.$start, $totalPrice . '円');

        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/public/sample.xlsx');
        $writer->save($path);
        return $path;
    }

    private function exportCount($param, $data){
        $inputFileName =storage_path('app/templates_invoice/'. $param['company']['code'].'/'.$param['invoice']['code'].'.xlsx');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
//        $spreadsheet->disableCalculationCache();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('H3', $param['export_date']);
        $sheet->setCellValue('H8', $param['order_date']);
        $sheet->setCellValue('H9', $param['ship_date']);

        $sheet->setCellValue('B11', $param['customer']['name']);
        $sheet->setCellValue('B12', $param['customer']['ship_address']);
        $sheet->setCellValue('B13', $param['customer']['ship_phone_number']);
        $sheet->setCellValue('B14', $param['customer']['ship_fax']);

        $sheet->setCellValue('B16', $param['merchant']['name']);
        $sheet->setCellValue('B17', $param['merchant']['address']);
        $sheet->setCellValue('B18', $param['merchant']['phone_number']);
        $sheet->setCellValue('B19', $param['merchant']['fax']);
        $sheet->setCellValue('H4', $param['invoice_number']);

        $start = 22;
        $orderLine = 0;
        $totalTsubo = 0;
        $totalPrice = 0;

        $allprice = CustomerJobCategory::where('company_id', $param['company']['id'])->where('customer_id', $param['customer']['id'])->where('invoice_order', '>', 0)->get()->toArray();
        $listPrice = [];
        foreach ($allprice as $item){
            $listPrice[$item['job_category_id']] = floatval($item['price_count']);
        }
        //dd($listPrice);
        $emptyStye =  array(
            'font' => array('bold' => false),
            'color' => ['argb' => 'FFFFFF'],
        );
        foreach ($data as $job) {

            $jobcategory = $job['hacchyuu_naiyou'];
            $currentCate = JobCategory::where('job_category_name', $jobcategory)->first();
            if(empty($currentCate)) {
                continue;
            }
            $currentCate = $currentCate->toArray();
            if(!isset($listPrice[$currentCate['id']])) {
                continue;
            }
            $price =  $listPrice[$currentCate['id']];
            if(empty($price)) {
                $price =  $currentCate['price_count'];
            }
            $start ++;
            $orderLine ++;

//            dd($job);

            $sheet->insertNewRowBefore($start);
            $sheet->getStyle('A'.$start.':'.'H'.$start)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffffff');
            $sheet->getStyle('A'.$start.':'.'H'.$start)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

            $sheet->getStyle('A'.$start.':'.'H'.$start)->applyFromArray($emptyStye);

            $sheet->setCellValue('A'.$start, $orderLine);


//            dd($formula);
            $sheet->setCellValue('B'.$start, $jobcategory);
            $sheet->setCellValue('C'.$start, $job['bukkenmei']);
            $sheet->setCellValue('D'.$start, $job['hacchyuumoto']);
            $sheet->setCellValue('E'.$start,  '棟');
            $sheet->setCellValue('F'.$start, $price);
            $totalTsubo ++;
            $priceLine = $price;
            $priceLine = round($priceLine,0);

            $totalPrice += $priceLine;
            $sheet->setCellValue('G'.$start, $priceLine . '円');

        }
        $sheet->getStyle('E22:E'.$start)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('G22:G'.$start)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $start++;
        $sheet->setCellValue('E'.$start, number_format($totalTsubo). '坪');
        $sheet->setCellValue('G'.$start,number_format($totalPrice, 0) . '円');

        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/public/sample.xlsx');
        $writer->save($path);
        return $path;
    }

    private function exportMoney($param, $data){
        $inputFileName =storage_path('app/templates_invoice/'. $param['company']['code'].'/'.$param['invoice']['code'].'.xlsx');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('H3', $param['export_date']);
        $sheet->setCellValue('H8', $param['order_date']);
        $sheet->setCellValue('H9', $param['ship_date']);

        $sheet->setCellValue('B11', $param['customer']['name']);
        $sheet->setCellValue('B12', $param['customer']['ship_address']);
        $sheet->setCellValue('B13', $param['customer']['ship_phone_number']);
        $sheet->setCellValue('B14', $param['customer']['ship_fax']);

        $sheet->setCellValue('B16', $param['merchant']['name']);
        $sheet->setCellValue('B17', $param['merchant']['address']);
        $sheet->setCellValue('B18', $param['merchant']['phone_number']);
        $sheet->setCellValue('B19', $param['merchant']['fax']);
        $sheet->setCellValue('H4', $param['invoice_number']);
        $start = 22;
        $orderLine = 0;
        $emptyStye =  array(
            'font' => array('bold' => false),
            'color' => ['argb' => 'FFFFFF'],
        );
        $totalTsubo = 0;
        $totalPrice = 0;
        foreach ($data as $job) {
            $start ++;
            $orderLine ++;
            $sheet->insertNewRowBefore($start);

            $sheet->getStyle('A'.$start.':'.'H'.$start)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffffff');
            $sheet->getStyle('A'.$start.':'.'H'.$start)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

            $sheet->getStyle('A'.$start.':'.'H'.$start)->applyFromArray($emptyStye);


            $sheet->setCellValue('A'.$start, $orderLine);
            $sheet->setCellValue('B'.$start, $job['item']);
            $sheet->setCellValue('C'.$start, $job['description']);
            $sheet->setCellValue('D'.$start, '');
            $sheet->setCellValue('E'.$start,  '月分');
            $price = $job['amount'];
            $sheet->setCellValue('F'.$start, $price);
            $totalTsubo ++;
            $priceLine = $price;
            $totalPrice += $priceLine;
            $sheet->setCellValue('G'.$start, $priceLine. '円');

        }
        $sheet->getStyle('G22:G'.$start)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $start++;
        $sheet->setCellValue('G'.$start,$totalPrice . '円');

        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/public/sample.xlsx');
        $writer->save($path);
        return $path;
    }

    public function exportTeamSummary($param, $data){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $borderStyle = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,

                ),
            ),
        );


        $sheet->getPageSetup()->setVerticalCentered(true);

        $sheet->getRowDimension('4')->setRowHeight(120);
        $sheet->setCellValue('A1',"SUMMARY OF WORK_".strtoupper($param['company']['code'])  ."_".date("Y/m"));
        $sheet->mergeCells('A3:A5')->setCellValue('A3', 'Work areas')->getStyle('A3:A5')->applyFromArray($borderStyle);
        $sheet->mergeCells('B3:E4')->setCellValue('B3', '作成者')->getStyle('B3:E4')->applyFromArray($borderStyle);
        $sheet->mergeCells('F3:G3')->setCellValue('F3', '労働時間')->getStyle('F3:G3')->applyFromArray($borderStyle);
        $sheet->setCellValue('F4','普通')->getStyle("E4")->applyFromArray($borderStyle);
        $sheet->setCellValue('G4','残業')->getStyle("F4")->applyFromArray($borderStyle);

        $sheet->mergeCells('H3:H4')->setCellValue('H3', '作業時間')->getStyle('H3:H4')->applyFromArray($borderStyle);
        $sheet->mergeCells('I3:I4')->setCellValue('I3', 'チェック時間')->getStyle('I3:I4')->applyFromArray($borderStyle);
        $sheet->mergeCells('N3:O4')->setCellValue('N3', 'チェック者')->getStyle('N3:O4')->applyFromArray($borderStyle);
        $sheet->mergeCells('P3:Q4')->setCellValue('P3', '応援者')->getStyle('P3:Q4')->applyFromArray($borderStyle);

        $sheet->setCellValue('N5','坪数')->getStyle("N5")->applyFromArray($borderStyle);
        $sheet->setCellValue('O5','棟数')->getStyle("O5")->applyFromArray($borderStyle);

        $sheet->setCellValue('P5','坪数')->getStyle("P5")->applyFromArray($borderStyle);
        $sheet->setCellValue('Q5','棟数')->getStyle("Q5")->applyFromArray($borderStyle);


        $sheet->setCellValue('B5','Code')->getStyle("H5")->applyFromArray($borderStyle);
        $sheet->setCellValue('C5','Username')->getStyle("H5")->applyFromArray($borderStyle);
        $sheet->setCellValue('D5','Name')->getStyle("H5")->applyFromArray($borderStyle);
        $sheet->setCellValue('E5','Position')->getStyle("H5")->applyFromArray($borderStyle);


        $sheet->setCellValue('F5','（日間）')->getStyle("H5")->applyFromArray($borderStyle);
        $sheet->setCellValue('G5','（時間）')->getStyle("G5")->applyFromArray($borderStyle);
        $sheet->setCellValue('H5','（時間）')->getStyle("H5")->applyFromArray($borderStyle);
        $sheet->setCellValue('I5','（時間）')->getStyle("I5")->applyFromArray($borderStyle);

        $sheet->mergeCells('J3:J5')->setCellValue('J3', '作成ポイント')->getStyle('J3:J5')->applyFromArray($borderStyle);
        $sheet->mergeCells('K3:K5')->setCellValue('K3', 'チェックポイント')->getStyle('K3:K5')->applyFromArray($borderStyle);
        $sheet->mergeCells('L3:L5')->setCellValue('L3', '応援ポイント')->getStyle('L3:L5')->applyFromArray($borderStyle);
        $sheet->mergeCells('M3:M5')->setCellValue('M3', 'ポイント合計')->getStyle('M3:M5')->applyFromArray($borderStyle);

        //dd($param);
        $users = Staff::where('team_id', $param['team']['id'])->get();
        $companyId = $param['company']['id'];
        $jobMasters = JobMaster::where('company_id', $companyId)->where('is_enable', true)->orderBy('summary_order')->get();
        $index = "Q";
        $list = [];
        $firstIndex = 'Q';
        $list['作業時間'] = "H";
        $list['チェック時間'] = "I";
        $list['作成ポイント'] = "J";
        $list['チェックポイント'] = "K";
        $list['応援ポイント'] = "L";
        $list['ポイント合計'] = "M";
        $list['チェック者_坪数'] = "N";
        $list['チェック者_棟数'] = "O";
        $list['応援者_坪数'] = "P";
        $list['応援者_棟数'] = "Q";

        $subCategory = config('role.sub_category');
        $group =  [
            '1' => "棟数",// can
            '2' => "坪数",//dien tich
            '3' => "坪数 & 棟数"
        ];
        $listCatCal = [];
        $listMasCal = [];
        foreach ($jobMasters as $jobMaster)
        {
            $name = $jobMaster->job_master_name;
            $jobCategories = JobCategory::where('job_master_id',$jobMaster->id)->orderBy('summary_order')->get();
            $type = $jobMaster->summary_type;
            $group = $jobMaster->summary_group;
            if($jobMaster->is_rank){
                $code = $jobMaster->rank_code;
                $listMasCal[] = $code;
                $index ++;
                $sheet->setCellValue($index.'3',$name)->getStyle($index.'3')->applyFromArray($borderStyle);
                if($group == 1 ) {
                    $sheet->getColumnDimension($index)->setWidth(20);
                    $sheet->setCellValue($index.'5', "棟数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                    $list[$code."_棟数"] = $index;
                }
                if($group == 2) {
                    $sheet->getColumnDimension($index)->setWidth(20);
                    $sheet->setCellValue($index.'5', "坪数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                    $list[$code."_坪数"] = $index;
                }

                if($group == 3) {
                    $sheet->getColumnDimension($index)->setWidth(20);
                    $sheet->setCellValue($index.'5',"坪数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                    $list[$code."_坪数"] = $index;

                    $start = $index;
                    $index ++;
                    $end = $index;
                    $sheet->mergeCells($start."3:".$end."3");
                    $sheet->mergeCells($start."4:".$end."4");
                    $sheet->getStyle($start."3:".$end."3")->applyFromArray($borderStyle);
                    $sheet->getStyle($start."4:".$end."4")->applyFromArray($borderStyle);

                    $sheet->getColumnDimension($index)->setWidth(20);
                    $sheet->setCellValue($index.'5', "棟数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                    $list[$code."_棟数"] = $index;
                }
            } else {

                if($type == 'job_category'){

                    foreach ($jobCategories as $jobCategory) {
                        $nameCategory =  $jobCategory->job_category_name;
                        if(isset($subCategory[$nameCategory])) {
                            continue;
                        }
                        $index ++;
                        $start = $index;
                        if($firstIndex == "Q") {
                            $firstIndex = $index;
                            //  echo ($firstIndex)."_ ";
                        }

                        $listCatCal[] = $nameCategory;
                        $sheet->setCellValue($index.'4', $nameCategory);
                        if($group == 1) {
                            $sheet->getColumnDimension($index)->setWidth(20);
                            $sheet->setCellValue($index.'5', "棟数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                            $list[$nameCategory."_棟数"] = $index;
                        }
                        if($group == 2) {
                            $sheet->getColumnDimension($index)->setWidth(20);
                            $sheet->setCellValue($index.'5', "坪数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                            $list[$nameCategory."_坪数"] = $index;
                        }

                        if($group == 3) {
                            $sheet->getColumnDimension($index)->setWidth(20);
                            $sheet->setCellValue($index.'5', "坪数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                            $list[$nameCategory."_坪数"] = $index;
                            $index ++;
                            $sheet->getColumnDimension($index)->setWidth(20);
                            $sheet->setCellValue($index.'5', "棟数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                            $list[$nameCategory."_棟数"] = $index;
                        }
                        $end = $index;
                        if($start == $end) {
                            $sheet->setCellValue($start.'4',$jobCategory->job_category_name)->getStyle($start."4")->applyFromArray($borderStyle);
                        } else {
                            $sheet->mergeCells($start."4:".$end."4")->setCellValue($start.'4',$jobCategory->job_category_name)->getStyle($start."4:".$end."4")->applyFromArray($borderStyle);
                        }



                    }
                    $index--;
                    $last = $index;
                    if($firstIndex == "Q") {
                        break;
                    }
                    if($firstIndex != $last){
                        $sheet->mergeCells($firstIndex."3:".$last."3")->setCellValue($firstIndex.'3',$name)->getStyle($firstIndex."3:".$last."3")->applyFromArray($borderStyle);
                    } else {

                        $sheet->setCellValue($firstIndex.'3',$name)->getStyle($index.'3')->applyFromArray($borderStyle);

                    }

                }
                if($type == 'code'){
                    $ranks = MasterJobRank::where('master_job_id',$jobMaster->id)->get();
                    foreach ($ranks as $rank) {
                        $code =  $rank->rank_code;

                        $index ++;
                        $start = $index;
                        if($firstIndex == "Q") {
                            $firstIndex = $index;
                            //  echo ($firstIndex)."_ ";
                        }

                        $listMasCal[] = $code;
                        $cuses =Customer::where('summary_rank', $code)->get();
                        $names = [];
                        if($cuses->count()){
                            $cuses = $cuses->toArray();
                            $names = array_column($cuses,'name');
                        }
                        $titleItem = implode("\n", $names);
                        if($group == 1) {
                            $sheet->getColumnDimension($index)->setWidth(20);
                            $sheet->setCellValue($index.'5', "棟数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                            $list[$code."_棟数"] = $index;
                        }
                        if($group == 2) {
                            $sheet->getColumnDimension($index)->setWidth(20);
                            $sheet->setCellValue($index.'5', "坪数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                            $list[$code."_坪数"] = $index;
                        }

                        if($group == 3) {
                            $sheet->getColumnDimension($index)->setWidth(20);
                            $sheet->setCellValue($index.'5', "坪数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                            $list[$code."_坪数"] = $index;
                            $index ++;
                            $sheet->getColumnDimension($index)->setWidth(20);
                            $sheet->setCellValue($index.'5', "棟数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                            $list[$code."_棟数"] = $index;
                        }
                        $end = $index;
                        if($start == $end) {
                            $sheet->setCellValue($start.'4',$titleItem)->getStyle($start."4")->applyFromArray($borderStyle);
                        } else {
                            $sheet->mergeCells($start."4:".$end."4")->setCellValue($start.'4',$titleItem)->getStyle($start."4:".$end."4")->applyFromArray($borderStyle);
                        }



                    }
                    $index--;
                    $last = $index;
                    if($firstIndex == "Q") {
                        break;
                    }
                    if($firstIndex != $last){
                        $sheet->mergeCells($firstIndex."3:".$last."3")->setCellValue($firstIndex.'3',$name)->getStyle($firstIndex."3:".$last."3")->applyFromArray($borderStyle);
                    } else {

                        $sheet->setCellValue($firstIndex.'3',$name)->getStyle($index.'3')->applyFromArray($borderStyle);

                    }
                }

                if($type == 'customer'){
                    foreach ($jobCategories as $jobCategory) {

                        $listRelate = CustomerJobCategory::where('job_category_id',$jobCategory->id)->get();
                        $titleItem = [];
                        if($listRelate->count()){
                            $cusId =array_column($listRelate->toArray(), 'customer_id');
                            $cuses = Customer::whereIn('id', $cusId)->get();
                            if($cuses->count()){
                                $titleItem =array_column($cuses->toArray(), 'name');

                            }

                        }
                        $nameCategory =  $jobCategory->job_category_name;
                        $index ++;
                        $start = $index;
                        if($firstIndex == "Q") {
                            $firstIndex = $index;
                            //  echo ($firstIndex)."_ ";
                        }
                        $listCatCal[] = $nameCategory;
//                        dd($index.'4',$titleItem, $name, implode('<br>', $titleItem).'customer');
                        $titleItem = implode("\n", $titleItem);
                        if($group == 1) {
                            $sheet->getColumnDimension($index)->setWidth(20);
                            $sheet->setCellValue($index.'5', "棟数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                            $list[$nameCategory."_棟数"] = $index;
                        }
                        if($group == 2) {
                            $sheet->getColumnDimension($index)->setWidth(20);
                            $sheet->setCellValue($index.'5', "坪数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                            $list[$nameCategory."_坪数"] = $index;
                        }

                        if($group == 3) {
                            $sheet->getColumnDimension($index)->setWidth(20);
                            $sheet->setCellValue($index.'5', "坪数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                            $list[$nameCategory."_坪数"] = $index;
                            $index ++;
                            $sheet->getColumnDimension($index)->setWidth(20);
                            $sheet->setCellValue($index.'5', "棟数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                            $list[$nameCategory."_棟数"] = $index;
                        }
                        $end = $index;
                        if($start == $end) {
                            $sheet->setCellValue($start.'4',$titleItem)->getStyle($start."4")->applyFromArray($borderStyle);
                        } else {
                            $sheet->mergeCells($start."4:".$end."4")->setCellValue($start.'4',$titleItem)->getStyle($start."4:".$end."4")->applyFromArray($borderStyle);
                        }



                    }
                    $index--;
                    $last = $index;
                    if($firstIndex == "Q") {
                        continue;
                    }
                    if($firstIndex != $last){
                        $sheet->mergeCells($firstIndex."3:".$last."3")->setCellValue($firstIndex.'3',$name)->getStyle($firstIndex."3:".$last."3")->applyFromArray($borderStyle);
                    } else {

                        $sheet->setCellValue($firstIndex.'3',$name)->getStyle($index.'3')->applyFromArray($borderStyle);

                    }

                }

            }
            $firstIndex = "Q";
        }

        $sheet->getColumnDimension('A')->setWidth(7);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getColumnDimension('M')->setWidth(20);
        $sheet->getColumnDimension('N')->setWidth(20);
        $sheet->getColumnDimension('O')->setWidth(20);
        $sheet->getColumnDimension('P')->setWidth(20);
        $sheet->getColumnDimension('Q')->setWidth(20);

        $sheet->getStyle('A3:'.$index.'5')
            ->getAlignment()
            ->setWrapText(true)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getStyle('A3:I5')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('ffae7b')
        ;
        $sheet->getStyle('N3:'.$index.'5')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('ffae7b')
        ;
        $sheet->getStyle('J3:M5')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('A9E2F3')
        ;

       // dd($listMasCal,$listCatCal);
        $listData = [];
        $resultData = [];
        foreach ($data as $item) {
            $staffCode = $item['staff_code'];
            if(isset($resultData[$staffCode])){
                $staffData = $resultData[$staffCode];
            } else {
                $staffData = [
                    '作業時間' => [],
                    'チェック時間' => []
                ];
            }
            $jobCat = $item['hacchyuu_naiyou'];

            if(!empty($jobCat)) {
                $cat = $jobCat;
                if(isset($subCategory[$jobCat])) {
                    $cat = $subCategory[$jobCat];
                }
                if(isset($staffData[$cat])) {
                    $staffData[$cat][] = $item['tsubosuu'];
                } else {
                    $staffData[$cat] = [$item['tsubosuu']];
                }
            }

            $rank  =  $item['rank'];
            if(!empty($rank) &&  in_array($rank, $listMasCal )) {
                if(isset($staffData[$jobCat])) {
                    $staffData[$rank][] = $item['tsubosuu'];
                } else {
                    $staffData[$rank] = [$item['tsubosuu']];
                }
            }
            $staffData['作業時間'][] =  round($item['sagyou_jikan']/60,2);//H time work
            $staffData['チェック時間'][] = round($item['chekkku_jikan']/60, 2);//I time check

            $resultData[$staffCode] = $staffData;
            if(isset($listData[$staffCode])){
                $totalData = $listData[$staffCode];
            } else {
                $totalData['作業時間'] = [];
                $totalData['チェック時間'] = [];
                $totalData['作成ポイント'] = [];
                $totalData['応援ポイント'] = [];
                $totalData['チェックポイント'] = [];
            }
            $totalData['作業時間'][] =  round($item['sagyou_jikan']/60,2);//H
            $totalData['チェック時間'][] = round($item['chekkku_jikan']/60, 2);//I
            if(!empty($jobCat)) {
                try {
                    $cusotmer = $item['tokuisakimei'];
                    $jobcategory = JobCategory::where('job_category_name',$jobCat)->first();
                    if (empty($jobcategory)) {

                        dd($jobCat, 'empty job category name');
                    }
                    $jobCatId = $jobcategory->id;
                    $jobMasterId = $jobcategory->job_master_id;
                    $cusAlias = CustomerAliasName::where('alias_name',$cusotmer)->first();
                    if (empty($cusAlias)) {

                        dd('empty alias name', $cusotmer);
                    }
                    $customerConfig = CustomerJobCategory::where([['customer_id', $cusAlias->customer_id], ['job_master_id', $jobMasterId], ['job_category_id',$jobCatId]])->first();
                    if(empty($customerConfig)) {

                        dd("Missing setting for job category",$cusotmer,$jobCat, [['customer_id', $cusAlias->customer_id], ['job_master_id', $jobMasterId], ['job_category_id',$jobCatId]]);
                    }

                    $support = (float)($item['hosawariai']);
                    // $support = "30%";
                    $max = (float)(max([$item['nani_keisuu'],$item['kibo_keisuu'], $item['naniten']]));
                    if(!empty($support)) {
                        $support = floatval(str_replace("%", '',$support))/100;
                    }

                    $point = $max*(float)($customerConfig->point) *(float)(1 - $support) ;
                    if($support > 0) {
                        $pointSupport = $max* (float)($customerConfig->point) - $point;
                    }

                    $totalData['作成ポイント'][] = $point;
                    $totalData['作成ポイント'][] = 0;
                    $listData[$staffCode] = $totalData;

                    $pointCheck = $max*$customerConfig->point_check;
                    $checkStaffCode = $item['chekkusha'];

                    if(isset($listData[$checkStaffCode])){
                        $checkStaffData = $listData[$checkStaffCode];
                        if(!isset($checkStaffData['チェックポイント'])){
                            $checkStaffData['チェックポイント'] = [];
                        }
                    } else {
                        $checkStaffData = [
                            'チェックポイント' => []
                        ];
                    }
                    $checkStaffData['チェックポイント'][] = $pointCheck;
                    $listData[$checkStaffCode] = $checkStaffData;
                    if($support > 0) {
                        $supportStaffCode = $item['hosanin'];
                        if(isset($listData[$checkStaffCode])){
                            $supportStaffData = $listData[$supportStaffCode];
                            if(!isset($supportStaffData['応援ポイント'])){
                                $supportStaffData['応援ポイント'] = [];
                            }
                        } else {
                            $supportStaffData = [
                                '応援ポイント' => []
                            ];
                        }
                        $supportStaffData['応援ポイント'][] = $pointSupport;
                        $listData[$supportStaffCode] = $supportStaffData;
                    }

                } catch (\Exception $ex) {
                    dd($ex);
                }

            }

        }

       // for N, O column
        foreach ($data as $item) {
            $staffCode = $item['chekkusha'];
            if(isset($resultData[$staffCode])){
                $staffData = $resultData[$staffCode];
            } else {
                $staffData = [];
            }
            $jobCat = 'チェック者';
            if(isset($staffData[$jobCat])) {
                $staffData[$jobCat][] = $item['tsubosuu'];
            } else {
                $staffData[$jobCat] = [$item['tsubosuu']];
            }
            $resultData[$staffCode] = $staffData;
        }
        foreach ($resultData as $key => $item) {
            if(isset($listData[$key])){
                $staffData = $listData[$key];
            } else {
                $staffData = [
                    '作業時間' => [],
                    'チェック時間' => []
                ];
            }
            foreach ($item as $cat => $line) {
                if(!in_array($cat, ['作業時間','チェック時間'])) {
                    $staffData[$cat."_棟数"] = count($line);
                    $staffData[$cat."_坪数"] = array_sum($line);
                } else {
                    $staffData[$cat] = array_merge($staffData[$cat], $line);
                }

            }
            $staffData['作業時間'] =  array_sum((array)@$staffData['作業時間']);//H
            $staffData['チェック時間'] = array_sum((array)@$staffData['チェック時間']);//I
            $staffData['作成ポイント'] = array_sum((array)@$staffData['作成ポイント']);//J
            $staffData['チェックポイント'] = array_sum((array)@$staffData['チェックポイント']);//K
            $staffData['応援ポイント'] = array_sum((array)@$staffData['応援ポイント']);//L

            $staffData['ポイント合計'] = $staffData['作成ポイント'] + $staffData['チェックポイント'] + $staffData['応援ポイント'];
            $listData[$key] = $staffData;

        }
        $start = 6;
        $orderLine = 0;
        $listUser = [];
        foreach ($users as $user) {
            $listUser[$user->code] = $user;
        }
        $listTotal = [];
        foreach ($users as  $user) {
            $code = $user->code;
            $sheet->setCellValue('A'.$start, $orderLine);
            $sheet->setCellValue('C'.$start, $code);

            $sheet->setCellValue('B'.$start, $user->staff_id);
            $sheet->setCellValue('D'.$start, $user->name);
            $sheet->setCellValue('E'.$start, $user->position);
            $value = (array)@$listData[$code];
            if( count($value) > 0){
                foreach ($value as $cate=>$line) {
                    try {
                        $position = @$list[$cate];
                        if(empty($position)) {
                             continue;
                        }
                        $sheet->setCellValue("$position$start", $line);
                        if(isset($listTotal[$position])) {
                            $listTotal[$position][] = $line;
                        }else {
                            $listTotal[$position] = [$line];
                        }
                    } catch (\Exception $ex) {
                       // dd($cate, $list, $ex->getMessage(), $value);
                    }

                }
            }
            $start ++;
            $orderLine++;
        }

        foreach ($listTotal as $key => $item) {
            $sheet->setCellValue($key.$start, array_sum($item));
        }

        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/public/sample.xlsx');
        $writer->save($path);
        return $path;
    }


    public function exportCustomerSummary($param, $data){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $borderStyle = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,

                ),
            ),

        );


        $sheet->getPageSetup()->setVerticalCentered(true);

        $sheet->getRowDimension('4')->setRowHeight(120);
        $sheet->setCellValue('A1',"SUMMARY OF WORK ".strtoupper($param['company']['code'])  ."_".date("Y/m"));
        $sheet->mergeCells('A3:A5')->setCellValue('A3', 'Work areas')->getStyle('A3:A5')->applyFromArray($borderStyle);
        $sheet->mergeCells('B3:C4')->setCellValue('B3', 'ご得意先様')->getStyle('B3:C4')->applyFromArray($borderStyle);
        $sheet->setCellValue('B5','Customer')->getStyle("B5")->applyFromArray($borderStyle);
        $sheet->setCellValue('C5','Alias')->getStyle("C5")->applyFromArray($borderStyle);




        //dd($param);
  //      $users = Staff::where('team_id', $param['team']['id'])->get();
        $companyId = $param['company']['id'];
        $jobMasters = JobMaster::where('company_id', $companyId)->where('is_enable', true)->orderBy('summary_order')->get();
        $index = "C";
        $list = [];
        $firstIndex = 'C';


        $subCategory = config('role.sub_category');

        $group =  [
            '1' => "棟数",// can
            '2' => "坪数",//dien tich
            '3' => "坪数 & 棟数"
        ];
        $listCatCal = [];
        $listMasCal = [];
        foreach ($jobMasters as $jobMaster)
        {
            $name = $jobMaster->job_master_name;

            $jobCategories = JobCategory::where('job_master_id',$jobMaster->id)->orderBy('summary_order')->get();
            $type = $jobMaster->summary_type;
            $group = $jobMaster->summary_group;
            if($jobMaster->is_rank){
                $code = $jobMaster->rank_code;
                $listMasCal[] = $code;
                $index ++;
                $sheet->setCellValue($index.'3',$name)->getStyle($index.'3')->applyFromArray($borderStyle);
                if($group == 1 ) {
                    $sheet->getColumnDimension($index)->setWidth(20);
                    $sheet->setCellValue($index.'5', "棟数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                    $list[$code."_棟数"] = $index;
                }
                if($group == 2) {
                    $sheet->getColumnDimension($index)->setWidth(20);
                    $sheet->setCellValue($index.'5', "坪数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                    $list[$code."_坪数"] = $index;
                }

                if($group == 3) {
                    $sheet->getColumnDimension($index)->setWidth(20);
                    $sheet->setCellValue($index.'5', "坪数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                    $list[$code."_坪数"] = $index;
                    $start = $index;
                    $index ++;
                    $end = $index;
                    $sheet->mergeCells($start."3:".$end."3");
                    $sheet->mergeCells($start."4:".$end."4");
                    $sheet->getStyle($start."3:".$end."3")->applyFromArray($borderStyle);
                    $sheet->getStyle($start."4:".$end."4")->applyFromArray($borderStyle);

                    $sheet->getColumnDimension($index)->setWidth(20);
                    $sheet->setCellValue($index.'5', "棟数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                    $list[$code."_棟数"] = $index;
                }
            } else {

                if($type == 'job_category'){

                    foreach ($jobCategories as $jobCategory) {
                        $nameCategory =  $jobCategory->job_category_name;
                        if(isset($subCategory[$nameCategory])) {
                            continue;
                        }
                        $index ++;
                        $start = $index;
                        if($firstIndex == "C") {
                            $firstIndex = $index;
                        }

                        $listCatCal[] = $nameCategory;
                        $sheet->setCellValue($index.'4', $nameCategory);
                        if($group == 1 ) {
                            $sheet->getColumnDimension($index)->setWidth(20);
                            $sheet->setCellValue($index.'5', "棟数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                            $list[$nameCategory."_棟数"] = $index;
                        }
                        if($group == 2) {
                            $sheet->getColumnDimension($index)->setWidth(20);
                            $sheet->setCellValue($index.'5', "坪数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                            $list[$nameCategory."_坪数"] = $index;
                        }

                        if($group == 3) {
                            $sheet->getColumnDimension($index)->setWidth(20);
                            $sheet->setCellValue($index.'5', "坪数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                            $list[$nameCategory."_坪数"] = $index;
                            $index ++;
                            $sheet->getColumnDimension($index)->setWidth(20);
                            $sheet->setCellValue($index.'5', "棟数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                            $list[$nameCategory."_棟数"] = $index;
                        }
                        $end = $index;
                        if($start == $end) {
                            $sheet->setCellValue($start.'4',$jobCategory->job_category_name)->getStyle($start."4")->applyFromArray($borderStyle);
                        } else {
                            $sheet->mergeCells($start."4:".$end."4")->setCellValue($start.'4',$jobCategory->job_category_name)->getStyle($start."4:".$end."4")->applyFromArray($borderStyle);
                        }



                    }
                    $index--;
                    $last = $index;
                    if($firstIndex == "C") {
                        continue;
                    }
                    if($firstIndex != $last){
                        $sheet->mergeCells($firstIndex."3:".$last."3")->setCellValue($firstIndex.'3',$name)->getStyle($firstIndex."3:".$last."3")->applyFromArray($borderStyle);
                    } else {

                        $sheet->setCellValue($firstIndex.'3',$name)->getStyle($index.'3')->applyFromArray($borderStyle);

                    }

                }
                else {

                    if($type == 'code'){
                        $ranks = MasterJobRank::where('master_job_id',$jobMaster->id)->get();
                        foreach ($ranks as $rank) {
                            $code =  $rank->rank_code;

                            $index ++;
                            $start = $index;
                            if($firstIndex == "C") {
                                $firstIndex = $index;
                                //  echo ($firstIndex)."_ ";
                            }

                            $listMasCal[] = $code;
                            $cuses =Customer::where('summary_rank', $code)->get();
                            $names = [];
                            if($cuses->count()){
                                $cuses = $cuses->toArray();
                                $names = array_column($cuses,'name');
                            }
                            $titleItem = implode("\n", $names);
                            if($group == 1) {
                                $sheet->getColumnDimension($index)->setWidth(20);
                                $sheet->setCellValue($index.'5', "棟数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                                $list[$code."_棟数"] = $index;
                            }
                            if($group == 2) {
                                $sheet->getColumnDimension($index)->setWidth(20);
                                $sheet->setCellValue($index.'5', "坪数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                                $list[$code."_坪数"] = $index;
                            }

                            if($group == 3) {
                                $sheet->getColumnDimension($index)->setWidth(20);
                                $sheet->setCellValue($index.'5', "坪数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                                $list[$code."_坪数"] = $index;
                                $index ++;
                                $sheet->getColumnDimension($index)->setWidth(20);
                                $sheet->setCellValue($index.'5', "棟数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                                $list[$code."_棟数"] = $index;
                            }
                            $end = $index;
                            if($start == $end) {
                                $sheet->setCellValue($start.'4',$titleItem)->getStyle($start."4")->applyFromArray($borderStyle);
                            } else {
                                $sheet->mergeCells($start."4:".$end."4")->setCellValue($start.'4',$titleItem)->getStyle($start."4:".$end."4")->applyFromArray($borderStyle);
                            }



                        }
                        $index--;
                        $last = $index;
                        if($firstIndex == "C") {
                            break;
                        }
                        if($firstIndex != $last){
                            $sheet->mergeCells($firstIndex."3:".$last."3")->setCellValue($firstIndex.'3',$name)->getStyle($firstIndex."3:".$last."3")->applyFromArray($borderStyle);
                        } else {

                            $sheet->setCellValue($firstIndex.'3',$name)->getStyle($index.'3')->applyFromArray($borderStyle);

                        }

                    }

                    if($type == 'customer'){
                        try{
                            foreach ($jobCategories as $jobCategory) {

                                $listRelate = CustomerJobCategory::where('job_category_id',$jobCategory->id)->get();
                                $titleItem = [];
                                if($listRelate->count()){
                                    $cusId =array_column($listRelate->toArray(), 'customer_id');
                                    $cuses = Customer::whereIn('id', $cusId)->get();
                                    if($cuses->count()){
                                        $titleItem =array_column($cuses->toArray(), 'name');

                                    }

                                }
                                $nameCategory =  $jobCategory->job_category_name;
                                $index ++;
                                $start = $index;
                                if($firstIndex == "C") {
                                    $firstIndex = $index;
                                    //  echo ($firstIndex)."_ ";
                                }
                                ;
                                $listCatCal[] = $nameCategory;
                                $titleItem = implode("\n", $titleItem);
                                if($group == 1) {
                                    $sheet->getColumnDimension($index)->setWidth(20);
                                    $sheet->setCellValue($index.'5', "棟数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                                    $list[$nameCategory."_棟数"] = $index;
                                }
                                if($group == 2) {
                                    $sheet->getColumnDimension($index)->setWidth(20);
                                    $sheet->setCellValue($index.'5', "坪数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                                    $list[$nameCategory."_坪数"] = $index;
                                }

                                if($group == 3) {
                                    $sheet->getColumnDimension($index)->setWidth(20);
                                    $sheet->setCellValue($index.'5', "坪数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                                    $list[$nameCategory."_坪数"] = $index;
                                    $index ++;
                                    $sheet->getColumnDimension($index)->setWidth(20);
                                    $sheet->setCellValue($index.'5', "棟数" )->getStyle($index.'5')->applyFromArray($borderStyle);
                                    $list[$nameCategory."_棟数"] = $index;
                                }
                                $end = $index;
                                if($start == $end) {
                                    $sheet->setCellValue($start.'4',$titleItem)->getStyle($start."4")->applyFromArray($borderStyle);
                                } else {
                                    $sheet->mergeCells($start."4:".$end."4")->setCellValue($start.'4',$titleItem)->getStyle($start."4:".$end."4")->applyFromArray($borderStyle);
                                }



                            }
                            $index--;
                            $last = $index;
                            if($firstIndex == "C") {
                                continue;
                            }
                            if($firstIndex != $last){
                                $sheet->mergeCells($firstIndex."3:".$last."3")->setCellValue($firstIndex.'3',$name)->getStyle($firstIndex."3:".$last."3")->applyFromArray($borderStyle);
                            } else {

                                $sheet->setCellValue($firstIndex.'3',$name)->getStyle($index.'3')->applyFromArray($borderStyle);

                            }
                        } catch (\Exception $ex){
                            dd($ex->getMessage());
                        }


                    }

                }

            }
            $firstIndex = "C";
        }
//        dd($list);
        $sheet->getColumnDimension('A')->setWidth(7);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(50);

        $sheet->getStyle('A3:'.$index.'5')
            ->getAlignment()
            ->setWrapText(true)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B:C')->getAlignment()->setVertical('center');
        $sheet->getStyle('A3:'.$index.'5')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('ffae7b')
        ;

        $company = $param['company'];

        $customers = $company->customers->load('alias')->toArray();




   //      dd($listMasCal,$listCatCal, $list);
        $listData = [];
        $resultData = [];
        foreach ($data as $item) {
            $staffCode = $item['tokuisakimei'];
            if(isset($resultData[$staffCode])){
                $staffData = $resultData[$staffCode];
            } else {
                $staffData = [];
            }
            $jobCat = $item['hacchyuu_naiyou'];

            if(!empty($jobCat)) {
                $cat = $jobCat;
                if(isset($subCategory[$jobCat])) {
                    $cat = $subCategory[$jobCat];
                }

                if(isset($staffData[$cat])) {
                    $staffData[$cat][] = $item['tsubosuu'];
                } else {
                    $staffData[$cat] = [$item['tsubosuu']];
                }
            }

            $rank  =  $item['rank'];
            if(!empty($rank) &&  in_array($rank, $listMasCal )) {
                if(isset($staffData[$jobCat])) {
                    $staffData[$rank][] = $item['tsubosuu'];
                } else {
                    $staffData[$rank] = [$item['tsubosuu']];
                }
            }
            $resultData[$staffCode] = $staffData;
        }


        foreach ($resultData as $key => $item) {
            if(isset($listData[$key])){
                $staffData = $listData[$key];
            } else {
                $staffData = [
                ];
            }
            foreach ($item as $cat => $line) {

                $staffData[$cat."_棟数"] = count($line);
                $staffData[$cat."_坪数"] = array_sum($line);
            }

            $listData[$key] = $staffData;

        }

      //  dd($listData);


        $start = 6;
        $orderLine = 0;

       // dd($listData, $customers);
        $listTotal = [];
        foreach ($customers as  $customer) {

            if($customer['is_merchant']) {
                continue;
            }
            $begin = $start;
            $alias = $customer['alias'];
            if(empty($alias)) {
                $start ++;
                $orderLine++;
            }
            foreach ($alias as $item) {
                $sheet->setCellValue('A'.$start, $item['department']);
                $code = $item['alias_name'];
                $sheet->setCellValue('C'.$start, $code);
                $value = (array)@$listData[$code];
                if( count($value) > 0){
                    foreach ($value as $cate=>$line) {
                        try {
                            $position = @$list[$cate];
                            if(empty($position)) {
                                continue;
                            }
                            $sheet->setCellValue("$position$start", $line);
                            if(isset($listTotal[$position])) {
                                $listTotal[$position][] = $line;
                            }else {
                                $listTotal[$position] = [$line];
                            }
                        } catch (\Exception $ex) {
                            dd($cate, $list, $ex->getMessage(), $value);
                        }

                    }
                }
                $start ++;
                $orderLine++;
            }
            $end = $start -1;
            if($end > $begin) {
                $sheet->mergeCells('B'.$begin.':B'.$end)->setCellValue('B'.$begin, $customer['name'])->getStyle('B'.$begin.':B'.$end)->applyFromArray($borderStyle);
            } else {
                $sheet->setCellValue('B'.$begin, $customer['name'])->getStyle('B'.$begin)->applyFromArray($borderStyle);
            }




        }

        foreach ($listTotal as $key => $item) {
            $sheet->setCellValue($key.$start, array_sum($item));
        }

        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/public/sample.xlsx');
        $writer->save($path);
        return $path;
    }

    public function exportExpenseBranch($param){
        $data = ExpenseTransaction::where('branch_id',$param['branch_id'])
            ->where('currency', $param['currency'])
            ->where('expense_group','>', 1)
            ->whereBetween('expense_date', [$param['start_date'], $param['end_date']])
            ->whereNull('deleted_on')->get();
        $currency = strtolower($param['currency']);
        if ($currency == 'vnd') {
            return $this->exportCastVnd($param,$data);
        }
        if ($currency == 'usd') {
            return $this->exportCastUsd($param,$data);
        }
        if ($currency == 'jpy') {
            return $this->exportCastUsd($param,$data);
        }
    }

    public function exportBankTransaction($account, $param) {
        $data = ExpenseTransaction::where('bank_account_id',$account->id)
            ->where('expense_group', 1)->whereBetween('expense_date', [$param['start_date'], $param['end_date']])->whereNull('deleted_on')->get();
        $currency = strtolower($account->currency);
        if ($currency == 'vnd') {
            return $this->exportBankTransactionVnd($account, $param,$data);
        }
        if ($currency == 'usd') {
            return $this->exportBankTransactionUsd($account, $param,$data);
        }
        if ($currency == 'jpy') {
            return $this->exportBankTransactionUsd($account, $param,$data);
        }
       // dd($data);
        /*
         * "start_date" => "2021-05-01"
  "end_date" => "2021-05-29"
  "rate" => "12"
  "balance" => "123"
         */
        if ($currency == 'vnd') {
            return $this->exportBankTransactionVnd($account, $param,$data);
        }
        if ($currency == 'usd') {
            return $this->exportBankTransactionUsd($account, $param,$data);
        }
        if ($currency == 'jpy') {
            return $this->exportBankTransactionUsd($account, $param,$data);
        }
    }




    public function exportBankTransactionVnd($account, $param, $data) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $borderStyle = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,

                ),
            ),


        );
        $balance = $param['balance'];
        $currency = strtoupper($account->currency);
        $sheet->setCellValue('E5',"Số dư đầu kỳ \n初期残高 ")->getStyle("E5")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('F5',number_format($balance,2))->getStyle("F5")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('G5'," Đơn vị: $currency \nユニット: $currency  ")->getStyle("G5")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->mergeCells('A6:A7')->setCellValue('A6', 'Ngày - 時間')->getStyle('A6:A7')->applyFromArray($borderStyle);
        $sheet->mergeCells('B6:C6')->setCellValue('B6', 'Chứng từ - 書類コード	')->getStyle('B6:C6')->applyFromArray($borderStyle);
        $sheet->setCellValue('B7',"Ngày - 時間")->getStyle("B7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('C7',"Số - コード")->getStyle("C7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->mergeCells('D6:D7')->setCellValue('D6', 'Khách hàng - 顧客')->getStyle('D6:D7')->applyFromArray($borderStyle);
        $sheet->mergeCells('E6:E7')->setCellValue('E6', 'Nội dung - 内容')->getStyle('E6:E7')->applyFromArray($borderStyle);
        $sheet->mergeCells('F6:G6')->setCellValue('F6', 'Số tiền - 金額')->getStyle('F6:G6')->applyFromArray($borderStyle);
        $sheet->setCellValue('F7',"Thu - 収入")->getStyle("F7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('G7',"Chi - 支払")->getStyle("G7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);


        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(50);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);

        $sheet->getStyle('A6:G7')
            ->getAlignment()
            ->setWrapText(true)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $start = 8;
        $credited = 0;
        $debited = 0;
        foreach ($data as $item) {
            $sheet->setCellValue('A'.$start,$item->created_at->format("Y-m-d"))->getStyle('A'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('B'.$start,$item->expense_date)->getStyle('B'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('C'.$start,$item->transaction_code)->getStyle('C'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('D'.$start,$item->customer_name)->getStyle('D'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('E'.$start,$item->description)->getStyle('E'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            if ($item->type == 'credited') {
                $credited += floatval($item->amount);
                $sheet->setCellValue('F'.$start,$item->amount)->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('G'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            } else {
                $debited += floatval($item->amount);
                $sheet->setCellValue('G'.$start,$item->amount)->getStyle('G'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            }


            $start ++;
        }
        $sheet->mergeCells('A'.$start.':E'.$start)->setCellValue('A'.$start, 'Tổng cộng - 合計')->getStyle('A'.$start.':F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('F'.$start,$credited)->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('G'.$start,$debited)->getStyle('G'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $start ++;
        $sheet->mergeCells('A'.$start.':E'.$start)->setCellValue('A'.$start, 'Số dư cuối kỳ - 残高')->getStyle('A'.$start.':F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('F'.$start,number_format($balance+$credited-$debited, 2))->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);


        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/public/sample_bank_vnd_'.$account->id.'.xlsx');
        $writer->save($path);
        return $path;

    }
    public function exportBankTransactionUsd($account, $param, $data) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $borderStyle = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,

                ),
            ),


        );
        $balance = floatval($param['balance']);
        $currency = strtoupper($account->currency);
        $sheet->setCellValue('E5',"Số dư đầu kỳ \n初期残高 ")->getStyle("E5")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('F5',number_format($balance,2))->getStyle("F5")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('G5'," Đơn vị: $currency \nユニット: $currency  ")->getStyle("G5")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->mergeCells('A6:A7')->setCellValue('A6', 'Ngày - 時間')->getStyle('A6:A7')->applyFromArray($borderStyle);
        $sheet->mergeCells('B6:C6')->setCellValue('B6', 'Chứng từ - 書類コード	')->getStyle('B6:C6')->applyFromArray($borderStyle);
        $sheet->setCellValue('B7',"Ngày - 時間")->getStyle("B7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('C7',"Số - コード")->getStyle("C7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->mergeCells('D6:D7')->setCellValue('D6', 'Khách hàng - 顧客')->getStyle('D6:D7')->applyFromArray($borderStyle);
        $sheet->mergeCells('E6:E7')->setCellValue('E6', 'Nội dung - 内容')->getStyle('E6:E7')->applyFromArray($borderStyle);
        $sheet->mergeCells('F6:G6')->setCellValue('F6', 'Số tiền - 金額')->getStyle('F6:G6')->applyFromArray($borderStyle);
        $sheet->setCellValue('F7',"Thu - 収入")->getStyle("F7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('G7',"Chi - 支払")->getStyle("G7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);

        $sheet->mergeCells('H6:J6')->setCellValue('H6', 'Số tiền - 金額')->getStyle('H6:J6')->applyFromArray($borderStyle);
        $sheet->setCellValue('H7',"Tỷ giá - 為替")->getStyle("H7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('I7',"Thu - 収入")->getStyle("I7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('J7',"Chi - 支払")->getStyle("J7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);


        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(50);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);

        $sheet->getStyle('A6:J7')
            ->getAlignment()
            ->setWrapText(true)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $start = 8;
        $credited = 0;
        $debited = 0;
        $exchangeCredited = 0;
        $exchangeDebited = 0;
        foreach ($data as $item) {
            $sheet->setCellValue('A'.$start,$item->created_at->format("Y-m-d"))->getStyle('A'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('B'.$start,$item->expense_date)->getStyle('B'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('C'.$start,$item->transaction_code)->getStyle('C'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('D'.$start,$item->customer_name)->getStyle('D'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('E'.$start,$item->description)->getStyle('E'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $rate = floatval($item->rate);
            $sheet->setCellValue('H'.$start,number_format($rate,2))->getStyle('H'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $amount = floatval($item->amount);
            $exchangeAmount = $amount * $rate;
            if ($item->type == 'credited') {

                $credited += $amount;
                $exchangeCredited += $exchangeAmount;
                $sheet->setCellValue('F'.$start,number_format($amount,2))->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('G'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                $sheet->setCellValue('I'.$start,number_format($exchangeAmount,2))->getStyle('I'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('J'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            } else {
                $debited += $amount;
                $exchangeDebited += $exchangeAmount;
                $sheet->setCellValue('G'.$start,number_format($amount,2))->getStyle('G'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->setCellValue('J'.$start,number_format($exchangeAmount,2))->getStyle('J'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('I'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            }
            $start ++;
        }

        $sheet->mergeCells('A'.$start.':E'.$start)->setCellValue('A'.$start, 'Tổng cộng - 合計')->getStyle('A'.$start.':F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('F'.$start,number_format($credited, 2))->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('G'.$start,number_format($debited,2))->getStyle('G'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('H'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue('I'.$start,number_format($exchangeCredited, 2))->getStyle('I'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('J'.$start,number_format($exchangeDebited, 2))->getStyle('J'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $start ++;
        $sheet->mergeCells('A'.$start.':E'.$start)->setCellValue('A'.$start, 'Số dư cuối kỳ - 残高')->getStyle('A'.$start.':F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('F'.$start,number_format($balance+$credited-$debited, 2))->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);


        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/public/sample_bank_vnd_'.$account->id.'.xlsx');
        $writer->save($path);
        return $path;
    }

    public function exportCastVnd($param, $data) {

        $cashes = [];
        $visa = [];
        $nrp = [];
        foreach ($data as $item){
            if($item->expense_group == 2) {
                $cashes[] = $item;
            }
            if($item->expense_group == 3) {
                $nrp[] = $item;
            }
            if($item->expense_group == 4) {
                $visa[] = $item;
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $borderStyle = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
        );
        $balance = $param['balance'];
        $currency = strtoupper($param['currency']);
        $sheet->setCellValue('E5',"Số dư đầu kỳ \n初期残高 ")->getStyle("E5")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('F5',number_format($balance,2))->getStyle("F5")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('G5'," Đơn vị: $currency \nユニット: $currency  ")->getStyle("G5")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->mergeCells('A6:A7')->setCellValue('A6', 'Ngày - 時間')->getStyle('A6:A7')->applyFromArray($borderStyle);
        $sheet->mergeCells('B6:C6')->setCellValue('B6', 'Chứng từ - 書類コード	')->getStyle('B6:C6')->applyFromArray($borderStyle);
        $sheet->setCellValue('B7',"Ngày - 時間")->getStyle("B7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('C7',"Số - コード")->getStyle("C7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->mergeCells('D6:D7')->setCellValue('D6', 'Khách hàng - 顧客')->getStyle('D6:D7')->applyFromArray($borderStyle);
        $sheet->mergeCells('E6:E7')->setCellValue('E6', 'Nội dung - 内容')->getStyle('E6:E7')->applyFromArray($borderStyle);
        $sheet->mergeCells('F6:G6')->setCellValue('F6', 'Số tiền - 金額')->getStyle('F6:G6')->applyFromArray($borderStyle);
        $sheet->setCellValue('F7',"Thu - 収入")->getStyle("F7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('G7',"Chi - 支払")->getStyle("G7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);


        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(50);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);

        $sheet->getStyle('A6:G7')
            ->getAlignment()
            ->setWrapText(true)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $start = 8;
        $credited = 0;
        $debited = 0;
        foreach ($cashes as $item) {

            $sheet->setCellValue('A'.$start,$item->created_at->format("Y-m-d"))->getStyle('A'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('B'.$start,$item->expense_date)->getStyle('B'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('C'.$start,$item->transaction_code)->getStyle('C'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('D'.$start,$item->customer_name)->getStyle('D'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('E'.$start,$item->description)->getStyle('E'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            if ($item->type == 'credited') {
                $credited += floatval($item->amount);
                $sheet->setCellValue('F'.$start,$item->amount)->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('G'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            } else {
                $debited += floatval($item->amount);
                $sheet->setCellValue('G'.$start,$item->amount)->getStyle('G'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            }


            $start ++;
        }
        $sheet->mergeCells('A'.$start.':E'.$start)->setCellValue('A'.$start, 'Tổng cộng - 合計')->getStyle('A'.$start.':F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('F'.$start,$credited)->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('G'.$start,$debited)->getStyle('G'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $start ++;
        $sheet->mergeCells('A'.$start.':E'.$start)->setCellValue('A'.$start, 'Số dư cuối kỳ - 残高')->getStyle('A'.$start.':F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('F'.$start,number_format($balance+$credited-$debited, 2))->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $start +=3;
        $new = $start;
        $start++;
        $sheet->mergeCells('B'.$new.':F'.$start)->setCellValue('B'.$new,"HÓA ĐƠN KHÔNG THỰC CHI\n 請求書（支払ない）")->getStyle("B$new:F$start")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $start +=2;
        $new = $start;
        $sheet->mergeCells("B$start:C$start")->setCellValue("B$start", 'Chứng từ - 書類コード	')->getStyle("B$start:C$start")->applyFromArray($borderStyle);
        $start ++;
        $sheet->setCellValue("B$start","Ngày - 時間")->getStyle("B$start")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->setCellValue("C$start","Số - コード")->getStyle("C$start")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->mergeCells("D$new:D$start")->setCellValue("D$new", 'Khách hàng - 顧客')->getStyle("D$new:D$start")->applyFromArray($borderStyle);
        $sheet->mergeCells("E$new:E$start")->setCellValue("E$new", 'Nội dung - 内容')->getStyle("E$new:E$start")->applyFromArray($borderStyle);
        $sheet->mergeCells("F$new:F$start")->setCellValue("F$new", 'Số tiền - 金額')->getStyle("F$new:F$start")->applyFromArray($borderStyle);

        $sheet->getStyle("A$new:G$start")
            ->getAlignment()
            ->setWrapText(true)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $start ++;
        $total = 0;
        foreach ($nrp as $item) {
            $sheet->setCellValue('B'.$start,$item->expense_date)->getStyle('B'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('C'.$start,$item->transaction_code)->getStyle('C'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('D'.$start,$item->customer_name)->getStyle('D'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('E'.$start,$item->description)->getStyle('E'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $total += floatval($item->amount);
            $sheet->setCellValue('F'.$start,$item->amount)->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $start ++;
        }
        $sheet->mergeCells('B'.$start.':E'.$start)->setCellValue('B'.$start, 'Tổng cộng - 合計')->getStyle('B'.$start.':E'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('F'.$start,$total)->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $start +=3;
        $new = $start;
        $start++;
        $sheet->mergeCells('B'.$new.':F'.$start)->setCellValue('B'.$new,"HÓA ĐƠN THANH TOÁN BẰNG THẺ VISA\n 請求書（ビザカードで）")->getStyle("B$new:F$start")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $start +=2;
        $new = $start;
        $sheet->mergeCells("B$start:C$start")->setCellValue("B$start", 'Chứng từ - 書類コード	')->getStyle("B$start:C$start")->applyFromArray($borderStyle);
        $start ++;
        $sheet->setCellValue("B$start","Ngày - 時間")->getStyle("B$start")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->setCellValue("C$start","Số - コード")->getStyle("C$start")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->mergeCells("D$new:D$start")->setCellValue("D$new", 'Khách hàng - 顧客')->getStyle("D$new:D$start")->applyFromArray($borderStyle);
        $sheet->mergeCells("E$new:E$start")->setCellValue("E$new", 'Nội dung - 内容')->getStyle("E$new:E$start")->applyFromArray($borderStyle);
        $sheet->mergeCells("F$new:F$start")->setCellValue("F$new", 'Số tiền - 金額')->getStyle("F$new:F$start")->applyFromArray($borderStyle);

        $sheet->getStyle("A$new:G$start")
            ->getAlignment()
            ->setWrapText(true)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $start ++;
        $totalVisa = 0;
        foreach ($visa as $item) {
            $sheet->setCellValue('B'.$start,$item->expense_date)->getStyle('B'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('C'.$start,$item->transaction_code)->getStyle('C'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('D'.$start,$item->customer_name)->getStyle('D'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('E'.$start,$item->description)->getStyle('E'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $totalVisa += floatval($item->amount);
            $sheet->setCellValue('F'.$start,$item->amount)->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $start ++;
        }
        $sheet->mergeCells('B'.$start.':E'.$start)->setCellValue('B'.$start, 'Tổng cộng - 合計')->getStyle('B'.$start.':E'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('F'.$start,$totalVisa)->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);



        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/public/sample_bank_vnd_'.$param['branch_id'].'.xlsx');
        $writer->save($path);
        return $path;

    }
    public function exportCastUsd($param, $data) {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $borderStyle = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,

                ),
            ),


        );
        $balance = floatval($param['balance']);
        $currency = strtoupper($param['currency']);
        $sheet->setCellValue('E5',"Số dư đầu kỳ \n初期残高 ")->getStyle("E5")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('F5',number_format($balance,2))->getStyle("F5")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('G5'," Đơn vị: $currency \nユニット: $currency  ")->getStyle("G5")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->mergeCells('A6:A7')->setCellValue('A6', 'Ngày - 時間')->getStyle('A6:A7')->applyFromArray($borderStyle);
        $sheet->mergeCells('B6:C6')->setCellValue('B6', 'Chứng từ - 書類コード	')->getStyle('B6:C6')->applyFromArray($borderStyle);
        $sheet->setCellValue('B7',"Ngày - 時間")->getStyle("B7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('C7',"Số - コード")->getStyle("C7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->mergeCells('D6:D7')->setCellValue('D6', 'Khách hàng - 顧客')->getStyle('D6:D7')->applyFromArray($borderStyle);
        $sheet->mergeCells('E6:E7')->setCellValue('E6', 'Nội dung - 内容')->getStyle('E6:E7')->applyFromArray($borderStyle);
        $sheet->mergeCells('F6:G7')->setCellValue('F6', 'Số tiền - 金額')->getStyle('F6:G6')->applyFromArray($borderStyle);
        $sheet->setCellValue('F7',"Thu - 収入")->getStyle("F7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('G7',"Chi - 支払")->getStyle("G7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);

        $sheet->mergeCells('H6:J6')->setCellValue('H6', 'Số tiền - 金額')->getStyle('H6:J6')->applyFromArray($borderStyle);
        $sheet->setCellValue('H7',"Tỷ giá - 為替")->getStyle("H7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('I7',"Thu - 収入")->getStyle("I7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('J7',"Chi - 支払")->getStyle("J7")->applyFromArray($borderStyle)->getAlignment()->setWrapText(true);


        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(50);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);

        $sheet->getStyle('A6:J7')
            ->getAlignment()
            ->setWrapText(true)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $start = 8;
        $credited = 0;
        $debited = 0;
        $exchangeCredited = 0;
        $exchangeDebited = 0;
        foreach ($data as $item) {
            $sheet->setCellValue('A'.$start,$item->created_at->format("Y-m-d"))->getStyle('A'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('B'.$start,$item->expense_date)->getStyle('B'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('C'.$start,$item->transaction_code)->getStyle('C'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('D'.$start,$item->customer_name)->getStyle('D'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValue('E'.$start,$item->description)->getStyle('E'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $rate = floatval($item->rate);
            $sheet->setCellValue('H'.$start,number_format($rate,2))->getStyle('H'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $amount = floatval($item->amount);
            $exchangeAmount = $amount * $rate;
            if ($item->type == 'credited') {

                $credited += $amount;
                $exchangeCredited += $exchangeAmount;
                $sheet->setCellValue('F'.$start,number_format($amount,2))->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('G'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                $sheet->setCellValue('I'.$start,number_format($exchangeAmount,2))->getStyle('I'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('J'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            } else {
                $debited += $amount;
                $exchangeDebited += $exchangeAmount;
                $sheet->setCellValue('G'.$start,number_format($amount,2))->getStyle('G'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->setCellValue('J'.$start,number_format($exchangeAmount,2))->getStyle('J'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('I'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            }
            $start ++;
        }

        $sheet->mergeCells('A'.$start.':E'.$start)->setCellValue('A'.$start, 'Tổng cộng - 合計')->getStyle('A'.$start.':F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('F'.$start,number_format($credited, 2))->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('G'.$start,number_format($debited,2))->getStyle('G'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('H'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue('I'.$start,number_format($exchangeCredited, 2))->getStyle('I'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('J'.$start,number_format($exchangeDebited, 2))->getStyle('J'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $start ++;
        $sheet->mergeCells('A'.$start.':E'.$start)->setCellValue('A'.$start, 'Số dư cuối kỳ - 残高')->getStyle('A'.$start.':F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('F'.$start,number_format($balance+$credited-$debited, 2))->getStyle('F'.$start)->applyFromArray($borderStyle)->getAlignment()->setWrapText(true)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);


        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/public/sample_bank_'.$currency.'_'.$param['branch_id'].'.xlsx');
        $writer->save($path);
        return $path;
    }


    public function exportFinance($param){
        $code =  $param['company']['code'];
        switch ($code){
            case 'st':
                return $this->exportFinanceSt($param);
            case 'stcs':
                return $this->exportFinanceStcs($param);
            case 'tsv':
                return $this->exportFinanceTsv($param);
            default :
                return $this->exportFinanceSt($param);
        }
    }

    public function exportFinanceSt($param){
        $inputFileName =storage_path('app/finance/st.xlsx');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('E4', $param['rate-jpy']);
        $sheet->setCellValue('E5', $param['rate-usd']);

        $date = new \DateTime($param['cycle']);



        $sheet->setCellValue('D5', $date->format("Y/m/d"));
        $companyId = $param['company']['id'];
        $branches =  Branch::where('company_id', $companyId)->get();
        $cashVnd = 0;
        $cashUsd = 0;
        $cashJpy = 0;
        if($branches->count() > 0) {

            foreach ($branches as $branch) {
                $cashVnd += $branch->current_cash_balance;
                $cashUsd += $branch->current_usd_balance;
                $cashJpy += $branch->current_jpy_balance;
            }
        }
//        dd( $cashVnd , $cashUsd     ,$cashJpy);
        $sheet->setCellValue('C8',$cashVnd);
        $sheet->setCellValue('D8',$cashUsd);
        $sheet->setCellValue('E8',$cashJpy);

        $bankAccount = BankAccount::where('company_id', $companyId)->get();
        $bankVnd = 0;
        $bankUsd = 0;
        $bankJpy = 0;
        if($bankAccount->count() > 0) {
            foreach ($bankAccount as $bank) {
                if($bank->currency == 'VND')
                    $bankVnd += $bank->balance;
                if($bank->currency == 'USD')
                    $bankUsd += $bank->balance;
                if($bank->currency == 'JPY')
                    $bankJpy += $bank->balance;
            }
        }
        $sheet->setCellValue('C10',$bankVnd);
        $sheet->setCellValue('D11',$bankUsd);
        $sheet->setCellValue('E12',$bankJpy);

        $balanceCashVnd = $cashVnd;
        $balanceBankVnd = $bankVnd;
        $balanceBankJyp = $bankJpy * $param['rate-jpy'];
        $balanceBankUsd = $bankUsd * $param['rate-usd'];
        $total = $balanceCashVnd + $balanceBankVnd + $balanceBankJyp + $balanceBankUsd;
        $sheet->setCellValue('C41',$balanceCashVnd);
        $sheet->setCellValue('C42',$balanceBankVnd);
        $sheet->setCellValue('C43',$balanceBankJyp);
        $sheet->setCellValue('C44',$balanceBankUsd);
        $sheet->setCellValue('C45',$total);

        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/public/sample_bank_'.$param['company']['id'].'_'.date("Ym").'.xlsx');
        $writer->save($path);
        return $path;

    }

    public function exportFinanceStcs($param){
        $inputFileName =storage_path('app/finance/stcs.xlsx');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('C4', $param['rate-jpy']);
        $sheet->setCellValue('C5', $param['rate-usd']);

        $date = new \DateTime($param['cycle']);



        $sheet->setCellValue('B5', $date->format("Y/m/d"));
        $companyId = $param['company']['id'];
        $branches =  Branch::where('company_id', $companyId)->get();
        $cashVnd = 0;
        $cashUsd = 0;
        $cashJpy = 0;
        if($branches->count() > 0) {

            foreach ($branches as $branch) {
                $cashVnd += $branch->current_cash_balance;
                $cashUsd += $branch->current_usd_balance;
                $cashJpy += $branch->current_jpy_balance;
            }
        }
//        dd( $cashVnd , $cashUsd     ,$cashJpy);
        $sheet->setCellValue('C8',$cashVnd);
//        $sheet->setCellValue('D8',$cashUsd);
//        $sheet->setCellValue('E8',$cashJpy);

        $bankAccount = BankAccount::where('company_id', $companyId)->get();
        $bankVnd = 0;
        $bankUsd = 0;
        $bankJpy = 0;
        if($bankAccount->count() > 0) {
            foreach ($bankAccount as $bank) {
                if($bank->currency == 'VND')
                    $bankVnd += $bank->balance;
                if($bank->currency == 'USD')
                    $bankUsd += $bank->balance;
                if($bank->currency == 'JPY')
                    $bankJpy += $bank->balance;
            }
        }
        $sheet->setCellValue('C9',$bankVnd);
//        $sheet->setCellValue('D11',$bankUsd);
//        $sheet->setCellValue('E12',$bankJpy);

        $balanceCashVnd = $cashVnd;
        $balanceBankVnd = $bankVnd;
        $balanceBankJyp = $bankJpy * $param['rate-jpy'];
        $balanceBankUsd = $bankUsd * $param['rate-usd'];
        $total = $balanceCashVnd + $balanceBankVnd + $balanceBankJyp + $balanceBankUsd;
//        $sheet->setCellValue('C41',$balanceCashVnd);
//        $sheet->setCellValue('C42',$balanceBankVnd);
//        $sheet->setCellValue('C43',$balanceBankJyp);
//        $sheet->setCellValue('C44',$balanceBankUsd);
        $sheet->setCellValue('C10',$total);

        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/public/sample_bank_'.$param['company']['id'].'_'.date("Ym").'.xlsx');
        $writer->save($path);
        return $path;

    }

    public function exportFinanceTsv($param){
        $inputFileName =storage_path('app/finance/tsv.xlsx');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('E4', $param['rate-jpy']);
        $sheet->setCellValue('E5', $param['rate-usd']);

        $date = new \DateTime($param['cycle']);



        $sheet->setCellValue('D5', $date->format("Y/m/d"));
        $companyId = $param['company']['id'];
        $branches =  Branch::where('company_id', $companyId)->get();
        $cashVnd = 0;
        $cashUsd = 0;
        $cashJpy = 0;
        if($branches->count() > 0) {
            foreach ($branches as $branch) {
                $cashVnd += $branch->current_cash_balance;
                $cashUsd += $branch->current_usd_balance;
                $cashJpy += $branch->current_jpy_balance;
            }
        }
//        dd( $cashVnd , $cashUsd     ,$cashJpy);


        $bankAccount = BankAccount::where('company_id', $companyId)->get();
        // $bankVnd = 0;
        // $bankUsd = 0;
        // $bankJpy = 0;
        if($bankAccount->count() > 0) {
            foreach ($bankAccount as $bank) {
//                if($bank->currency == 'VND')
//                  $bankVnd += $bank->balance;
//              if($bank->currency == 'USD')
//                  $bankUsd += $bank->balance;
//              if($bank->currency == 'JPY')
//                  $bankJpy += $bank->balance;
                if($bank->name == 'vcb')
                    $Vcb = $bank->balance;
                if($bank->name == '503')
                    $Smbc503 = $bank->balance;
                if($bank->name == '502')
                    $Smbc502 = $bank->balance;
                if($bank->name == '501')
                    $Smbc501 = $bank->balance;
                if($bank->name == '500')
                    $Smbc500 = $bank->balance;
            }
        }
        $vnd = $cashVnd + $Vcb + $Smbc503 + $Smbc501;
        $usd = $cashUsd + $Smbc502 + $Smbc500;
        $jpy = $cashJpy;

//        $branches =  Branch::where('company_id', $companyId)->get();
//        $cashVnd = $bankVnd;
//        $cashUsd = $bankUsd;
//        $cashJpy = $bankJpy;
//        if($branches->count() > 0) {

//            foreach ($branches as $branch) {
//                $cashVnd += $branch->current_cash_balance;
//                $cashUsd += $branch->current_usd_balance;
//                $cashJpy += $branch->current_jpy_balance;
//            }
//        }

        $sheet->setCellValue('C8',$cashVnd);
        $sheet->setCellValue('D8',$cashUsd);
        $sheet->setCellValue('C9',$Vcb);
        $sheet->setCellValue('C10',$Smbc503);
        $sheet->setCellValue('D11',$Smbc502);
        $sheet->setCellValue('C12',$Smbc501);
        $sheet->setCellValue('D13',$Smbc500);
        $sheet->setCellValue('C14',$vnd);
        $sheet->setCellValue('D14',$usd);


//      $balanceCashVnd = $cashVnd;
//      $balanceBankVnd = $bankVnd;
//      $balanceBankJyp = $bankJpy * $param['rate-jpy'];
//      $balanceBankUsd = $bankUsd * $param['rate-usd'];
//      $total = $balanceCashVnd + $balanceBankVnd + $balanceBankJyp + $balanceBankUsd;
//      $sheet->setCellValue('C41',$balanceCashVnd);
//      $sheet->setCellValue('C42',$balanceBankVnd);
//      $sheet->setCellValue('C43',$balanceBankJyp);
//      $sheet->setCellValue('C44',$balanceBankUsd);
//      $sheet->setCellValue('C45',$total);

        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/public/sample_bank_'.$param['company']['id'].'_'.date("Ym").'.xlsx');
        $writer->save($path);
        return $path;

    }

}
