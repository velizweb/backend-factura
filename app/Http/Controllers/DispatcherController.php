<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Dispatcher, AuditUser};
use DataTables; 
use App\Helpers\AuditHelper;
use JWTAuth;

class DispatcherController extends Controller
{
    public function __construct(){
       #$this->middleware('jwt.auth');
    }

    public function index(){
        $dispatchers = Dispatcher::all();
        return $dispatchers;
    }
    
    public function indexDt($id) {
        $arr = [
            ['dispatcher.company_id', $id],
            ['dispatcher.is_deleted', '<>', 1]            
        ];
        //$dispatchers = Dispatcher::with('company')->select("dispatcher.*")->where('company_id',$id);
        $dispatchers = Dispatcher::with('company', 'identification_type')->select("dispatcher.*")->where($arr);
        //AuditHelper::Audit($dispatchers[0]->company_id, 'listar proveedores');        
        return DataTables::eloquent($dispatchers)->make(true);
    }

    public function indexFilter($id){
        $arr = [
            ['dispatcher.company_id', $id],
            ['dispatcher.is_deleted', '<>', 1]            
        ];
        $dispatchers = Dispatcher::with('company', 'identification_type')->select("dispatcher.*")->where($arr)->get();
        return $dispatchers;
    }

    public function show($id) {        
        $dispatcher = Dispatcher::where($id)->First();
        //AuditHelper::Audit($request['company'], 'ver proveedor: ' . $dispatcher->firstlast_name_social_reason);
        return $dispatcher;
    }

    public function search(Request $request){
        $dispatcher = Dispatcher::where('social_reason', $request['data'])
                        ->orWhere('comercial_name',  $request['data'])
                        ->First();

        return $dispatcher;
    }

    public function edit($id) {        
        $dispatcher = Dispatcher::with('company', 'identification_type')->select("dispatcher.*")->where('id', $id)->first();
        //AuditHelper::Audit($request['company'], 'ver proveedor: ' . $dispatcher->firstlast_name_social_reason);
        return $dispatcher;
    }    

    public function store(Request $request) { 
        $dispatcher = Dispatcher::create($request->all());        
        return 1;
    }

    public function update(Request $request, $id) {        
        $dispatcher = Dispatcher::findOrfail($id)->update($request->all());
        //AuditHelper::Audit($dispatcher->company_id, 'editar proveedor: ' . $dispatcher->firstlast_name_social_reason); 
        return 1;                            
    }

    public function destroy($id){
        $dispatcher = Dispatcher::where('id', $id)->First();

        $dispatcher->is_active = 0;
        $dispatcher->is_deleted = 1;
        $dispatcher->update();

        return 1;

    }

}
