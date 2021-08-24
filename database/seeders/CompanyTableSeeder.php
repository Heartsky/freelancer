<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\InvoiceType;
use App\Models\Role;
use App\Services\DataImport;
use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $import = app(DataImport::class);
        $invoiceCo = InvoiceType::where('code', 'count')->first();
        $invoiceAc = InvoiceType::where('code', 'acreage')->first();
        $invoiceMo= InvoiceType::where('code', 'money')->first();

        $company = new Company();
        $company->name = 'Son Thanh';
        $company->code = 'st';
        $company->address  = 'address';
        $company->email  = 'email';
        $company->phone_number  = 'phone';
        $company->tax_code  = 'tax_number';
        $company->fax  = 'fax';
        $company->save();

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

        $company->invoiceTypes()->attach($invoiceCo->id);
        $company->invoiceTypes()->attach($invoiceAc->id);
        $company->invoiceTypes()->attach($invoiceMo->id);


        $import->importJob($company->id);
        $import->importStaff($company->id);
        $import->importCustomer($company->id);
        $import->importTeam($company->id);

        $company = new Company();
        $company->name = 'TSV';
        $company->code = 'tsv';
        $company->address  = 'address';
        $company->email  = 'email';
        $company->phone_number  = 'phone';
        $company->tax_code  = 'tax_number';
        $company->fax  = 'fax';
        $company->save();
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

        $company->invoiceTypes()->attach($invoiceCo->id);
        $company->invoiceTypes()->attach($invoiceAc->id);
        $import->importJob($company->id);
        $import->importStaff($company->id);
        $import->importCustomer($company->id);
        $import->importTeam($company->id);




    }
}
