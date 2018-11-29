<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailConfiguration extends Model
{
    protected $table = 'mail_configuration';
    public $timestamps = false;
    public $fillable = ['company_id', 'subject', 'body', 'legend', 'host_server', 'port', 'user', 'password', 'server_type_id', 'security_type_id', 'identification_type_id', 'is_active', 'is_deleted'];

    protected $guarded = [];

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function mailservertype(){
    	return $this->belongsTo(EntityMasterdata::class, 'server_type_id', 'id');
    }

    public function securitytype(){
    	return $this->belongsTo(EntityMasterdata::class, 'security_type_id', 'id');
    }

    public function identificationtype(){
    	return $this->belongsTo(EntityMasterdata::class, 'identification_type_id', 'id');
    }

    
}
