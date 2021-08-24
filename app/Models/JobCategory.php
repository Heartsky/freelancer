<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function jobMaster()
    {
        return $this->hasOne(JobMaster::class,'id','job_master_id');
    }
}
