<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCustomer extends Model
{
    use HasFactory;
    protected $table = 'company_customer';
    protected $fillable = ['company_id', 'customer_id'];
}
