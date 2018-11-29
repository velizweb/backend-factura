<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = 'warehouse';
    public $timestamps = false;
    protected $fillable = ['id','name','location','company_id','branch_id','is_active', 'is_deleted'];
    protected $guarded = [];


    public function company(){
    	return $this->belongsTo(Company::class);
    }

    public function branch(){
        return $this->belongsTo(BranchOffice::class, 'branch_id', 'id');
    }

    public function warehouseProduct()
    {
        return $this->hasMany(WarehouseProduct::class);
    }
}
