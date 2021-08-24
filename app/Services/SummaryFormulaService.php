<?php
namespace App\Services;

class SummaryFormulaService  extends BaseService {




    /**
     * @param $list
     * 'sumifs(
    Cột "Sagyou jikan 作業時間"; [AI]
    Cột "Người thực hiện";
    Ô tại cột D)/60
     */
    public function calculateColumnG($list){

        $colVal = $this->getColumn('AI');
        $colCond = $this->getColumn('C');
        $colCheck = $this->getColumn('D');
        $total = 0;

        foreach ($list as $item){
            if($item[$colCond] == $item[$colCheck]) {
                $total += floatval(@$item[$colVal]);
            }

        }
        $result = $total/60;
        return $result;

    }

    /**
     * 'sumifs(
    Cột "Chekkku jikanチェック時間";
    Cột "Chekkushaチェック者";
    Ô tại cột D)/60
     */

    public function calculateColumnH($list){

        $colVal = $this->getColumn('AQ');
        $colCond = $this->getColumn('AM');
        $colCheck = $this->getColumn('D');
        $total = 0;

        foreach ($list as $item){
            if($item[$colCond] == $item[$colCheck]) {
                $total += floatval(@$item[$colVal]);
            }

        }
        $result = $total/60;
        return $result;

    }

    /**
     *
     * sumifs(
    Cột "Point thiết kế";
    Cột "Người thực hiện";
    Ô tại cột D)
     */

    public function calculateColumnI($list){

        $colVal = $this->getColumn('AZ');
        $colCond = $this->getColumn('C');
        $colCheck = $this->getColumn('D');
        $total = 0;

        foreach ($list as $item){
            if($item[$colCond] == $item[$colCheck]) {
                $total += floatval(@$item[$colVal]);
            }

        }
        $result = $total;
        return $result;

    }

    /**
     *
     * sumifs(
    Cột "Point check";
    Cột "Chekkushaチェック者";
    Ô tại cột D)
     */

    public function calculateColumnJ($list){

        return false;

    }

    /**
     * "sum(
    Cột 作成ポイント;
    Cột チェックポイント;
    Cột 応援ポイント)"
     */
    public function calculateColumnL($list){

        return false;

    }

    /**
     * @param $list
     * @return float|int
     * 'sumifs(
    Cột "Tsubosuu坪数";
    Cột "Chekkushaチェック者";
    Ô tại cột D)
     */
    public function calculateColumnM($list){

        $colVal = $this->getColumn('AL');
        $colCond = $this->getColumn('AQ');
        $colCheck = $this->getColumn('D');
        $total = 0;

        foreach ($list as $item){
            if($item[$colCond] == $item[$colCheck]) {
                $total += floatval(@$item[$colVal]);
            }

        }
        $result = $total;
        return $result;

    }

    /**
     * @param $list
     * @return float|int
     * sum(countifs(
    Cột "Chekkushaチェック者";
    Ô tại cột D)
     */
    public function calculateColumnN($list){
        $colCond = $this->getColumn('AM');
        $colCheck = $this->getColumn('D');
        $total = 0;
        foreach ($list as $item){
            if($item[$colCond] == $item[$colCheck]) {
                $total ++;
            }
        }
        $result = $total;
        return $result;
    }

    /**
     * sumifs(
    Cột "Tsubosuu坪数";
    Cột "Người thực hiện";
    Ô tại cột D;
    Cột "Hacchyuu naiyou発注内容";
    "新規（無）")
     */

    public function calculateColumnQ($list){

        $colVal = $this->getColumn('AL');
        $colCond = $this->getColumn('C');
        $colCheck = $this->getColumn('D');
        $total = 0;
        $colCheck2 = $this->getColumn('AP');
        $valueCheck2 = "新規（無）";
        foreach ($list as $item){
            if(($item[$colCond] == $item[$colCheck])&& $item[$colCheck2] == $valueCheck2 ) {
                $total += floatval(@$item[$colVal]);
            }

        }
        $result = $total;
        return $result;

    }


    /**
     * sum(countifs(
    Cột "Người thực hiện";
    Ô tại cột D;
    Cột "Hacchyuu naiyou発注内容";
    "新規（無）")
     */

    public function calculateColumnR($list){


        $colCond = $this->getColumn('C');
        $colCheck = $this->getColumn('D');
        $total = 0;
        $colCheck2 = $this->getColumn('AP');
        $valueCheck2 = "新規（無）";
        foreach ($list as $item){
            if(($item[$colCond] == $item[$colCheck])&& $item[$colCheck2] == $valueCheck2 ) {
                $total ++;
            }

        }
        $result = $total;
        return $result;

    }

    /**
     * sumifs(
    Cột "Tsubosuu坪数";
    Cột "Người thực hiện";
    Ô tại cột D;
    Cột "Hacchyuu naiyou発注内容";
    "訂正（無）")
     */
    public function calculateColumnS($list){

        $colVal = $this->getColumn('AL');
        $colCond = $this->getColumn('C');
        $colCheck = $this->getColumn('D');
        $total = 0;
        $colCheck2 = $this->getColumn('AP');
        $valueCheck2 = "訂正（無）";
        foreach ($list as $item){
            if(($item[$colCond] == $item[$colCheck])&& $item[$colCheck2] == $valueCheck2 ) {
                $total += floatval(@$item[$colVal]);
            }

        }
        $result = $total;
        return $result;

    }

    /*
     * sum(countifs(
Cột "Người thực hiện";
Ô tại cột D;
Cột "Hacchyuu naiyou発注内容";
"訂正（無）")
     */

    public function calculateColumnT($list){


        $colCond = $this->getColumn('C');
        $colCheck = $this->getColumn('D');
        $total = 0;
        $colCheck2 = $this->getColumn('AP');
        $valueCheck2 = "訂正（無）";
        foreach ($list as $item){
            if(($item[$colCond] == $item[$colCheck])&& $item[$colCheck2] == $valueCheck2 ) {
                $total ++;
            }

        }
        $result = $total;
        return $result;

    }

    /**
     * umifs(
    Cột "Tsubosuu坪数";
    Cột "Người thực hiện";
    Ô tại cột D;
    Cột "Hacchyuu naiyou発注内容";
    "最終のみ（無）")
     */
    public function calculateColumnU($list){

        $colVal = $this->getColumn('AL');
        $colCond = $this->getColumn('C');
        $colCheck = $this->getColumn('D');
        $total = 0;
        $colCheck2 = $this->getColumn('AP');
        $valueCheck2 = "最終のみ（無）";
        foreach ($list as $item){
            if(($item[$colCond] == $item[$colCheck])&& $item[$colCheck2] == $valueCheck2 ) {
                $total += floatval(@$item[$colVal]);
            }

        }
        $result = $total;
        return $result;

    }

    /*
     * sum(countifs(
Cột "Người thực hiện";
Ô tại cột D;
Cột "Hacchyuu naiyou発注内容";
"最終のみ（無）")
     */

    public function calculateColumnV($list){


        $colCond = $this->getColumn('C');
        $colCheck = $this->getColumn('D');
        $total = 0;
        $colCheck2 = $this->getColumn('AP');
        $valueCheck2 = "訂正（無）";
        foreach ($list as $item){
            if(($item[$colCond] == $item[$colCheck])&& $item[$colCheck2] == $valueCheck2 ) {
                $total ++;
            }

        }
        $result = $total;
        return $result;

    }

}
