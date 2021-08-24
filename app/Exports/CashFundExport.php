<?php

namespace App\Exports;

use App\CashFund;
use Maatwebsite\Excel\Concerns\FromCollection;

class CashFundExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return CashFund::all();
    }
}
