<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditUser extends Model
{
    protected $table = 'audit_user';
    public $timestamps = false;
    protected $guarded = [];

    public function user(){
    	return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function company(){
    	return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
