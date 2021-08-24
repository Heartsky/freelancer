<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id','branch_id', 'company_id', 'name','account_name','account_number', 'cif','swift_code','currency'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }


    public function getCustomerName(){
        $customer = $this->customer;
        if(!empty($customer))
        {
            return $customer->name;
        }
        return  "";
    }

    public function getCompanyName(){
        $company = $this->company;
        if(!empty($company))
        {
            return $company->name;
        }
        return  "";
    }
}
