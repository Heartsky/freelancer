<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAliasName extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'alias_name', 'alias_code'];
}
