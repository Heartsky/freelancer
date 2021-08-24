<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerJobCategory extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function jobMaster()
    {
        return $this->belongsTo(JobMaster::class);
    }

    public function getJobMasterName(){
        $jobMaster = $this->jobMaster;
        if(!empty($jobMaster))
        {
            return $jobMaster->job_master_name;
        }
        return  "";
    }

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class);
    }

    public function getJobCategoryName(){
        $jobCategory = $this->jobCategory;
        if(!empty($jobCategory))
        {
            return $jobCategory->job_category_name;
        }
        return  "";
    }

}
