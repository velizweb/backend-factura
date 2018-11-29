<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyPlan extends Model
{
    protected $table = 'company_plan';
    public $timestamps = false;
    protected $guarded = [];
    protected $fillable= ['current_counter', 'start_date', 'end_date', 'company_id', 'plan_id', 'is_active'];

	public function company(){
    	return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function plan(){
    	return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }

}
