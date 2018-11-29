<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchOffice extends Model
{
    protected $table = 'branch_office';
    public $timestamps = false;
    public $fillable = ['company_id', 'name', 'phone', 'address', 'email', 'emission_point', 'environment_type', 'emission_type', 'is_active', 'is_deleted'];
    protected $guarded = [];

    public function company(){
    	return $this->belongsTo(Company::class);
    }

    public function environmenttype(){
    	return $this->belongsTo(EntityMasterdata::class, 'environment_type', 'id');
    }

    public function emissiontype(){
    	return $this->belongsTo(EntityMasterdata::class, 'emission_type', 'id');
    }

    public function warehouses()
    {
        return $this->hasMahy(Warehouse::class, 'id', 'branch_id');
    }

}
