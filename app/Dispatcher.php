<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispatcher extends Model
{
    protected $table = 'dispatcher';
    public $timestamps = false;
    protected $guarded = [];
    protected $fillable = [
    						'identification_type_id', 
    						'identification_number',
    						'social_reason',
    						'phone',
    						'address',
    						'email',
    						'company_id', 
    						'is_active', 
    						'is_deleted'
    					];

    public function company(){
    	return $this->belongsTo(Company::class);
    }

    public function identification_type(){
    	return $this->belongsTo(EntityMasterdata::class, 'identification_type_id', 'id');
    }

}
