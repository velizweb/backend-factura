<?php

namespace App\Http\Controllers;

use App\{CompanyTaxYear, AuditUser};
use Illuminate\Http\Request;
use DataTables; 
use App\Helpers\AuditHelper;
use JWTAuth;

class CompanyTaxYearController extends Controller
{
    public function __construct(){
       #$this->middleware('jwt.auth');
    }

    public function indexDt($id) {        
        //$user = JWTAuth::parseToken()->authenticate();
        //AuditHelper::Audit($user->company_id, 'listar empresas');
        $companies = CompanyTaxYear::all();
        return DataTables::collection($companies)->make(true);
        //return  $companies;
    }

    public function index(){
        $companies = CompanyTaxYear::all();
        return  $companies;
    }
    
    public function show($id) {
        $company = Company::where('id', $id)->First();
        //AuditHelper::Audit($request['company'], 'ver empresa: ' . $company->name);
        return $company;
    }
    
    public function store(Request $request) {
    	//$user = JWTAuth::parseToken()->authenticate();
    	$company = CompanyTaxYear::create($request->all());				

        //AuditHelper::Audit($user->company_id, 'crear empresa: ' . $company->name);
        
        return 1;
    }
    
    public function update(Request $request, $id) {
        $company = CompanyTaxYear::findOrfail($id)->update($request->all());

        //AdutiHelper::Audit($request['company'], 'editar empresa: ' . $company->name);
        
        return 1;
    }

    public function destroy($id) {
    	$company = CompanyTaxYear::where('id', $id)->First();
        //AuditHelper::Audit($request['company'], 'eliminar empresa: ' . $company->name);
    	//$company->delete();
        return 1;
    }

}
