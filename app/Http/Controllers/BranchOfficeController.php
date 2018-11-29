<?php

namespace App\Http\Controllers;
use App\{BranchOffice, User, AuditUser};
use DataTables;
use Illuminate\Http\Request;
use App\Helpers\AuditHelper;
use JWTAuth;

class BranchOfficeController extends Controller
{   
    /*public function __construct(){
       $this->middleware('jwt.auth');
    }*/

    public function index($id){
        $arr = [
            ['company_id', $id],
            ['is_deleted', 0]            
        ];
        $branches = BranchOffice::where($arr)->get();
        return $branches;
    }

    public function indexDt($id) 
    {                        
        $arr = [
            ['company_id', $id],
            ['is_deleted', 0]            
        ];
        $branches = BranchOffice::with('company')->where($arr);
        //AuditHelper::Audit($request['company'], 'listar sucursales');
        return DataTables::eloquent($branches)->make(true);
        //return  $branches;
    }

    public function indexDtAll() 
    {                        
        $arr = [
            ['is_active', 1],
            ['is_deleted', 0]            
        ];
        $branches = BranchOffice::with('company')->where($arr);
        //AuditHelper::Audit($request['company'], 'listar sucursales');
        return DataTables::eloquent($branches)->make(true);
        //return  $branches;
    }
    
    public function show($id) {
        $branch = BranchOffice::where('id', $id)->First();	    
        //AuditHelper::Audit($request['company'], 'mostrar sucursal: ' . $branch->name);
        return $branch;		
    }

    public function edit($id) {
        $arr = [
            ['is_deleted', 0]            
        ];
        $branch = BranchOffice::with('company', 'environmenttype', 'emissiontype')->select('branch_office.*')->where('id', $id)->First();      
        $branch->company->logo = "";
        $branch->company->digital_certificate = "";
        //AuditHelper::Audit($request['company'], 'mostrar sucursal: ' . $branch->name);
        return $branch;     
    }
    
    public function store(Request $request, $id) {        	
        $branchOffice = BranchOffice::create([
            'address'           => $request["address"],
            'email'             => $request["email"],
            'emission_point'    => $request["emission_point"],
            'is_active'         => $request["is_active"],
            'emission_type'     => $request["emission_type"],
            'environment_type'  => $request["environment_type"],
            'name'              => $request["name"],
            'phone'             => $request["phone"],
            'company_id'        => $request["company_id"]
        ]);
        
        //AuditHelper::Audit($request['company'], 'crear sucursal: ' . $branchOffice->name);

     	return 1;
    }
   
    public function update(Request $request, $id) { 
        //BranchOffice::findOrfail($id)->update($request->all());

        $branchOffice = BranchOffice::where('id', $id)->First();
        $branchOffice->address	       = $request['address'];
        $branchOffice->email	       = $request['email'];
        $branchOffice->emission_point  = $request['emission_point'];
        $branchOffice->is_active       = $request["is_active"];
        $branchOffice->emission_type   = $request["emission_type"];
        $branchOffice->environment_type= $request["environment_type"];
        $branchOffice->name            = $request["name"];
        $branchOffice->phone           = $request["phone"];
        $branchOffice->company_id      = $request["company_id"];
        $branchOffice->save();


        //AuditHelper::Audit($branchOffice->company_id, 'editar sucursales: ' . $branchOffice->name);

      	return 1;
    }

    public function destroy($id) {
        $branchOffice = BranchOffice::where('id', $id)->First();        

        //AuditHelper::Audit($branchOffice->company_id, 'eliminar sucursal: ' . $branchOffice->name);
        
        $branchOffice->is_active    = 0;
        $branchOffice->is_deleted   = 1;

        $branchOffice->save();        

        return 1;
    }

    public function byCompany($id)
    {
        return BranchOffice::where('company_id', $id)->get();
    }
}
