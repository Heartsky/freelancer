<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_employee = new Role();
        $role_employee->name = 'Admin';
        $role_employee->description = 'Admin system';
        $role_employee->company_id  = '0';
        $role_employee->group  = 'admin';
        $role_employee->save();


    }
}
