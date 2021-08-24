<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'email', 'address','phone_number', 'fax', 'tax_code'];

    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class,'company_id','id');
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
    public function staffs()
    {
        return $this->hasMany(Staff::class,'company_id','id');
    }

    public function roles(){
        return $this->hasMany(Role::class,'company_id','id');
    }
    public function invoiceTypes(){
        return $this->belongsToMany(InvoiceType::class);
    }
}
