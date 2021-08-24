<?php
namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::whereNotNull('email')->delete();
        $role_admin = Role::where('name', 'Admin')->first();
        $employee = new User();
        $employee->name = 'Admin User';
        $employee->email = 'admin@admin.com';
        $employee->password = bcrypt('123456');
        $employee->save();
        $employee->roles()->attach($role_admin);


    }
}
