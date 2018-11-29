<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'client';
    protected $fillable = ['identification_type_id', 'identification_number','social_reason','comercial_name','phone','address','email','company_id', 'is_active', 'is_deleted'];
    public $timestamps = false;
    protected $guarded = [];

    public function company(){
    	return $this->belongsTo(Company::class);
    }

    public function identification_type(){
    	return $this->belongsTo(EntityMasterdata::class, 'identification_type_id', 'id');
    }
}
