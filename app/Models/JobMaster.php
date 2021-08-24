<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobMaster extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function jobCategoies()
    {
        return $this->hasMany(JobCategory::class,'job_master_id','id');
    }
}
