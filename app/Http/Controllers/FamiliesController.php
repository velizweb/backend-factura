<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Families;
use DataTables;
use App\Helpers\AuditHelper;
use JWTAuth;

class FamiliesController extends Controller
{
    public function __construct(){
       //$this->middleware('jwt.auth');
    }

    public function index(){
       $arr = [
            ['is_deleted', '<>', 1]            
        ];         
       $families = Families::where($arr)->get(); 
       return $families; 
    }

    public function indexDt() {
        $arr = [
            ['is_deleted', '<>', 1]            
        ];        
        $families = Families::where($arr)->get();            
        //AuditHelper::Audit($user->company_id, 'listar planes'); 
        return DataTables::collection($families)->make(true);       
        //return $plans;
    }

    public function show($id){
        $entity = Families::findOrFail($id);
        return $entity;
    }

    public function store(Request $request){

        $families = Families::create($request->all());

        return 1;

    }

    public function edit($id){
        $entity = Families::findOrFail($id);
        return $entity;
    }

    public function update(Request $request, $id) {
        //$user = JWTAuth::parseToken()->authenticate();

        $families = Families::findOrFail($id);

        $families->update($request->all());

        //AuditHelper::Audit($user->company_id, 'editar plan: ' . $plan->name);

        return 1;                            
    }

    public function destroy($id) { 
        //$user = JWTAuth::parseToken()->authenticate();
        $families = Families::findOrFail($id); 
        //AuditHelper::Audit($user->company_id, 'Eliminar plan: ' . $plan->name);
        $families->is_active = 0;
        $families->is_deleted = 1;
        $families->update();
        //$plan->delete();               
        return 1;
    }

}
