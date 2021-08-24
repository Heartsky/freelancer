<?php

namespace Database\Seeders;

use App\Models\InvoiceType;
use Illuminate\Database\Seeder;

class InvoiceTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type  = new InvoiceType();
        $type->name = 'Item Counting';
        $type->code = 'count';
        $type->save();

        $type  = new InvoiceType();
        $type->name = 'Money';
        $type->code = 'money';
        $type->save();

        $type  = new InvoiceType();
        $type->name = 'Acreage';
        $type->code = 'acreage';
        $type->save();
    }
}
