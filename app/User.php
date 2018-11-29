<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [        
    ];
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_active'        => 'string'
    ];

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function branch(){
        return $this->belongsTo(BranchOffice::class, 'branch_office_id', 'id');
    }

    public function role(){
        return $this->belongsTo(UserRole::class, 'id', 'users_id');
    }
}
