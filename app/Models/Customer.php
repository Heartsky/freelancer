<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class,'customer_id','id');
    }

    public function invoiceType()
    {
        return $this->belongsToMany(InvoiceType::class);
    }

    public function alias()
    {
        return $this->hasMany(CustomerAliasName::class,'customer_id','id');
    }
}
