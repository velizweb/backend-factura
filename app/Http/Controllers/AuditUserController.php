<?php

namespace App\Http\Controllers;
use App\{AuditUser, User};
use Illuminate\Http\Request;
use App\Helpers\AuditHelper;
use JWTAuth;
use DataTables;

class AuditUserController extends Controller
{
    public function __construct(){
       #$this->middleware('jwt.auth');
    }

    public function indexDt() {                
        return DataTables::eloquent(AuditUser::with('user', 'company'))->make(true);        
    }

    public function getActivityUser($id) {             
        //$user = JWTAuth::parseToken()->authenticate();
        $userToAudit = User::where('id', $id)->First();        
        //AuditHelper::Audit($user->company_id, 'auditoría de actividades de ' . $userToAudit->first_name . " " . $userToAudit->last_name ." /". $userToAudit->email);
        $activities = AuditUser::where('user_id', $id)->get();   
        return $activities;
    }    
}
