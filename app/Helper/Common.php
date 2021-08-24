<?php
namespace  App\Helper;

use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class Common {

    public static function formatDate($date) {

        $value = substr($date, 0, 10);
        $time = substr($date, 10);
        $data = explode('/', $value);
        $data = array_reverse($data);
        return trim(implode('-', $data). ' '. $time);
    }

    public static function getCompany(){
        $user = Auth::user();

        if($user->hasRole('admin')){
            $companies = Company::all();

        } else {
            $roles = $user->roles();
            $companies = [];
            foreach ($roles->get() as $role) {
                $company = $role->company()->first();
                //  dd($company);
                $customers = $company->customers;
                foreach ($customers as $customer){
                    $listCustomer[$customer->id] = $customer;
                }

                $companies[] = $company;
            }
        }
        return $companies;
    }

}
