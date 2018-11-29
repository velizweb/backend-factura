<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleModulePermission extends Model
{
    protected $table = 'role_module_permission';
    public $timestamps = false;
    protected $guarded = [];

    public function role(){
		return $this->belongsTo(EntityMasterdata::class, 'role_id', 'id');    
    }

    public function module(){
		return $this->belongsTo(EntityMasterdata::class, 'module_id', 'id');    
    }

    public function permission(){
		return $this->belongsTo(EntityMasterdata::class, 'permission_id', 'id');    
    }
}
