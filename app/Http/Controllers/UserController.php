<?php

namespace App\Http\Controllers;
use App\User;
use App\UserRole;
use App\EntityMasterdata;
use DataTables; 
use App\Helpers\AuditHelper;
use JWTAuth;

use Illuminate\Http\Request;

class UserController extends Controller
{	
	public function __construct(){
       //$this->middleware('jwt.auth');
    }

    public function index($id)
    {	
    	//$userAuth = JWTAuth::parseToken()->authenticate();
    	//AuditHelper::Audit($userAuth->company_id, 'listar usuarios');   
        $arr = [
            ['company_id', $id],
            ['is_deleted', 0],
            ['id', "NOT LIKE", 19]
        ];     
        $users = User::with('company')->where($arr);
		return DataTables::eloquent($users)->make(true);
        //return $users;
    }

     public function indexAll() {        
        $arr = [
            ['is_active', '1'],
            ['is_deleted', '0'],
            ['id', "NOT LIKE", 19]
        ];

        $users = User::with('company')->where($arr)->get();

        if(count($users) > 0)
          foreach ($users as $element) {            
            $element->company->logo = null;
            $element->company->digital_certificate = null;
          }

        return DataTables::collection($users)->make(true);
    }

    public function store(Request $request)
    {
    	//$userAuth = JWTAuth::parseToken()->authenticate();    	
        $user = User::create([
                    'username' 			=> $request['username'],                    
                    'email'         	=> $request['email'],                    
                    'is_active'       	=> $request['is_active'],                    
                    'password'      	=> bcrypt($request['password']),
                    'first_name'    	=> $request['first_name'],
                    'last_name'     	=> $request['last_name'],
                    'phone_number'      => $request['phone_number'],              
                    'company_id'    	=> $request['company_id'],
                    'branch_office_id'	=> $request['branch_office_id'],
                  ]);

        $role = UserRole::create([
                    'users_id' => $user->id,
                    'role_id'  => $request['role']
        ]);
        //AuditHelper::Audit($userAuth->company_id, 'crear usuario: ' . $user->first_name . " " . $user->last_name);
      	return 1;        
    }

    public function show($id)
    {
    	//$userAuth = JWTAuth::parseToken()->authenticate();    
    	$user = User::with('company', 'branch', 'role')->select("users.*")->where('id', $id)->First();
        $currentRole = EntityMasterdata::where('id', $user->role->role_id)->First();
        //AuditHelper::Audit($userAuth->company_id, 'ver usuario: ' . $user->first_name . " " . $user->last_name);
        return response()->json(compact('user', 'currentRole'));
    }

    public function update(Request $request, $id)
    {
		//$userAuth = JWTAuth::parseToken()->authenticate();    	
        $user = User::where('id', $id)->First();
        $user->username     = $request['username'];
        $user->email        = $request['email'];
        $user->is_active    = $request['is_active'];
        $user->first_name   = $request['first_name'];
        $user->last_name    = $request['last_name'];
        $user->phone_number = $request['phone_number'];
        
        if($request['password'] != ""){
            $user->password = bcrypt($request['password']);
        }

        if($request['company_id'] != ""){ 
            $user->company_id = $request['company_id']; 
        }
        
        if($request['branch_id'] != ""){ 
            $user->branch_office_id = $request['branch_id']; 
        }

        $user->save();
        
        $userRole = UserRole::where('id', $id)->First();
        $userRole->role_id = $request['role'];
        $userRole->save();
        
        //AuditHelper::Audit($userAuth->company_id, 'editar usuario: ' . $user->first_name . " " . $user->last_name);
        return 1;  
    }

    public function destroy($id)
    {	
    	//$userAuth = JWTAuth::parseToken()->authenticate();    	
        $user = User::where('id', $id)->First();
        //AuditHelper::Audit($userAuth->company_id, 'eliminar usuario: ' . $user->first_name . " " . $user->last_name);
		$user->is_active  = 0;
        $user->is_deleted = 1;
        $user->save();
		return 1; 
    }
}
