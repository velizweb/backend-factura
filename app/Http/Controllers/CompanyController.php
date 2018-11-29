<?php

namespace App\Http\Controllers;

use App\{Company, AuditUser};
use Illuminate\Http\Request;
use DataTables; 
use App\Helpers\AuditHelper;
use JWTAuth;

class CompanyController extends Controller
{
    public function __construct(){
       $this->middleware('jwt.auth', ['except' => ['index', 'indexDt', 'indexDtAll', 'show', 'store', 'updateCertificate', 'updateLogo', 'getCertificate', 'getLogo', 'destroy']]);
    }

    public function indexDt($id) {        
        //$user = JWTAuth::parseToken()->authenticate();
        //AuditHelper::Audit($user->company_id, 'listar empresas');
        $companies = Company::where('id', $id);
        return DataTables::eloquent($companies)->make(true);
        //return  $companies;
    }

    public function indexDtAll() {        
        //$user = JWTAuth::parseToken()->authenticate();
        //AuditHelper::Audit($user->company_id, 'listar empresas');
        $companies = Company::all();
        return DataTables::collection($companies)->make(true);
        //return  $companies;
    }

    public function index(){
        $companies = Company::all();
        return  $companies;
    }
    
    public function show($id) {
        $company = Company::with('emissiontype', 'environmenttype')->select("company.*")->where('id', $id)->First();
        //AuditHelper::Audit($request['company'], 'ver empresa: ' . $company->name);
        return $company;
    }
    
    public function store(Request $request) {
    	//$user = JWTAuth::parseToken()->authenticate();
    	$company = Company::create($request->all());				

        //AuditHelper::Audit($user->company_id, 'crear empresa: ' . $company->name);
        
        return $company;
    }
    
    public function update(Request $request, $id) {

        $company = Company::findOrfail($id);
        
		$company->name 			 = $request['name'];
        $company->comercial_name = $request['comercial_name'];
        $company->ruc            = $request['ruc'];
        $company->special_code   = $request['special_code'];
        $company->emission_code  = $request['emission_code'];
        $company->tax_year       = $request['tax_year'];
        $company->url            = $request['url'];
        $company->phone          = $request['phone'];
        $company->address        = $request['address'];
        $company->logo           = $request['logo'];
        $company->email          = $request['email'];
        $company->register_number= $request['register_number'];
        $company->is_artisan     = $request['is_artisan'];
        //$company->digital_certificate = '1';
        $company->digital_certificate_pass = $request['digital_certificate_pass'];
        $company->environment_type    = $request['environment_type'];
        $company->emission_type    = $request['emission_type'];
        $company->is_accounting    = $request['is_accounting'];
        $company->is_active        = $request['is_active'];
		// $company->save();

        $company->update();

        AuditHelper::Audit($request['company'], 'editar empresa: ' . $company->name);
        
        return 1;
    }

    public function updateCertificate(Request $request, $id){
        $company = Company::findOrfail($id);
        $company->digital_certificate = $request['certificate']; 
        $company->update();
        return "OK";
    }

    public function updateLogo(Request $request, $id){
        $company = Company::findOrfail($id);
        $company->logo = $request['logo']; 
        $company->update();
        return "OK";
    }

    public function getCertificate($id){
        $company = Company::findOrfail($id);
        $retorno = [
            ['digital_certificate', $company->digital_certificate]
        ];
        return $retorno;
    }

    public function getLogo($id){
        $company = Company::findOrfail($id);
        return $company->logo;
    }

    public function destroy($id) {
    	$company = Company::where('id', $id)->First();
        //AuditHelper::Audit($request['company'], 'eliminar empresa: ' . $company->name);
    	$company->delete();
        return 1;
    }

}
