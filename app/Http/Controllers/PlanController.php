<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plan;
use DataTables;
use App\Helpers\AuditHelper;
use JWTAuth;

class PlanController extends Controller
{
    public function __construct(){
       //$this->middleware('jwt.auth');
    }

    public function index(){
       $arr = [
            ['is_deleted', '<>', 1]            
        ];         
       $plans = Plan::where($arr)->get(); 
       return $plans; 
    }

    public function indexDt() {
        $arr = [
            ['is_deleted', '<>', 1]            
        ];        
        $plans = Plan::where($arr)->get();            
        //AuditHelper::Audit($user->company_id, 'listar planes'); 
        return DataTables::collection($plans)->make(true);       
        //return $plans;
    }

    public function show($id){
        $entity = Plan::findOrFail($id);
        return $entity;
    }

     public function store(Request $request){

        $plan = Plan::create($request->all());

        return 1;

    }

    public function edit($id){
        $entity = Plan::findOrFail($id);
        return $entity;
    }

    public function update(Request $request, $id) {
        //$user = JWTAuth::parseToken()->authenticate();

        $plan = Plan::findOrFail($id);

        $plan->update($request->all());

        //AuditHelper::Audit($user->company_id, 'editar plan: ' . $plan->name);

        return 1;                            
    }

    public function destroy($id) { 
        //$user = JWTAuth::parseToken()->authenticate();
        $plan = Plan::findOrFail($id); 
        //AuditHelper::Audit($user->company_id, 'Eliminar plan: ' . $plan->name);
        $plan->is_active = 0;
        $plan->is_deleted = 1;
        $plan->update();
        //$plan->delete();               
        return 1;
    }

}
