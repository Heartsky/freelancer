<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'staff_id','position','area', 'team_id','company_id'];
    protected $guarded = [];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function getTeamName(){
        $team = $this->team;
        if(!empty($team))
        {
            return $team->name;
        }
        return  "";
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
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
