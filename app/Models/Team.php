<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','name','leader_id','code'];

    public function company(){
        return $this->belongsTo(Company::class);
    }

}
