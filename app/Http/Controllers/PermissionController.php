<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables; 
use App\Helpers\AuditHelper;
use App\RoleModulePermission;
use App\UserRole;
use App\EntityMasterdata;
use JWTAuth;

class PermissionController extends Controller
{
    public function __construct(){
       //$this->middleware('jwt.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function indexDt()
    {
        $permissions = RoleModulePermission::with('role')
        ->select('role_module_permission.*')   
        ->where('role_module_permission.role_id', "<>", 139)     
        ->groupBy('role_module_permission.role_id')
        ->having('role_module_permission.is_deleted', 0);                
        return DataTables::eloquent($permissions)->make(true);
    }   

    public function check(Request $request){

        $accessResponse = 0;

        $dataToValidate = $request->only('user', 'module', 'action');        

        $userRole = UserRole::where('users_id', $dataToValidate['user'])->First();
        
        $entityArr = [
            ['entity_id', 7],
            ['code', $dataToValidate['module']]
        ];
        $module = EntityMasterdata::where($entityArr)->First();

        $arr = [
            ['role_id',     $userRole->role_id],
            ['module_id',   $module->id]
        ];
        $roleActions = RoleModulePermission::where($arr)->get();
        
        foreach ($roleActions as $action) {
            $permission = EntityMasterdata::where('id', $action->permission_id)->First();
            if($permission->code == $dataToValidate['action']){
                $accessResponse = 1;
                break;
            }
        }

        return $accessResponse;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {               
        $lastRole   = EntityMasterdata::where('entity_id', 6)->orderBy('code', 'desc')->First();
        $newCode = NULL;
        if($lastRole != NULL){
            $newCode = (int)$lastRole->code;
            $newCode++;
        }
        else
            $newCode = 1;
        
        $newRole = EntityMasterdata::create([
            'code'          => $newCode,
            'name'          => $request['role_name'],
            'description'   => $request['description'],
            'is_active'     => $request['is_active'],
            'entity_id'     => 6
        ]);

        foreach ($request['selected'] as $permission) {
            $auxPerm = json_decode($permission);
            $perm = RoleModulePermission::create([
                'role_id'           => $newRole->id,
                'module_id'         => $auxPerm->module_id,
                'permission_id'     => $auxPerm->permission_id,
                'is_active'         => $request['is_active']                
            ]);            
        }

        return 1;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = EntityMasterdata::where('id', $id)->First();
        $permissions = RoleModulePermission::where('role_id', $id)->get();
        return response()->json(compact("role", "permissions"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {        
        
        $role = EntityMasterdata::where('id', $id)->First();
        $role->name        = $request['role_name'];
        $role->description = $request['description'];
        $role->is_active   = $request['is_active'];
        $role->save();                

        $permissions = RoleModulePermission::where('role_id', $id)->get();
        foreach ($permissions as $perm) {
            $perm->delete();
        }        

        foreach ($request['selected[]'] as $permission) {
            $auxPerm = $permission;
            $perm = RoleModulePermission::create([
                'role_id'           => $role->id,
                'module_id'         => $auxPerm['module_id'],
                'permission_id'     => $auxPerm['permission_id'],
                'is_active'         => $request['is_active']                
            ]);            
        }

        return 1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = EntityMasterdata::where('id', $id)->First();        
        $role->is_active  = 0;
        $role->is_deleted = 1;
        $role->save(); 

        $permissions = RoleModulePermission::where('role_id', $id)->get();
        foreach ($permissions as $perm) {
            $perm->is_active  = 0;
            $perm->is_deleted = 1;
            $perm->save();
        }

        return 1;
    }
}
