<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Supplier, AuditUser};
use DataTables; 
use App\Helpers\AuditHelper;
use JWTAuth;

class SupplierController extends Controller
{
    public function __construct(){
       #$this->middleware('jwt.auth');
    }

    public function index(){
        $suppliers = Supplier::all();
        return $suppliers;
    }
    
    public function indexDt($id) {
        $arr = [
            ['supplier.company_id', $id],
            ['supplier.is_deleted', '<>', 1]            
        ];
        //$suppliers = Supplier::with('company')->select("supplier.*")->where('company_id',$id);
        $suppliers = Supplier::with('company', 'identification_type')->select("supplier.*")->where($arr);
        //AuditHelper::Audit($suppliers[0]->company_id, 'listar proveedores');        
        return DataTables::eloquent($suppliers)->make(true);
    }

    public function indexFilter($id){
        $arr = [
            ['supplier.company_id', $id],
            ['supplier.is_deleted', '<>', 1]            
        ];
        $suppliers = Supplier::with('company', 'identification_type')->select("supplier.*")->where($arr)->get();
        return $suppliers;
    }

    public function show($id) {        
        $supplier = Supplier::where($id)->First();
        //AuditHelper::Audit($request['company'], 'ver proveedor: ' . $supplier->firstlast_name_social_reason);
        return $supplier;
    }

    public function search(Request $request){
        $supplier = Supplier::where('social_reason', $request['data'])
                        ->orWhere('comercial_name',  $request['data'])
                        ->First();

        return $supplier;
    }

    public function edit($id) {        
        $supplier = Supplier::with('company', 'identification_type')->select("supplier.*")->where('id', $id)->first();
        //AuditHelper::Audit($request['company'], 'ver proveedor: ' . $supplier->firstlast_name_social_reason);
        return $supplier;
    }    

    public function store(Request $request) { 
        $supplier = Supplier::create($request->all());     
        //dd($supplier);
        return 1;
    }

    public function update(Request $request, $id) {  

        $supplier = Supplier::findOrfail($id)->update($request->all());
        //AuditHelper::Audit($supplier->company_id, 'editar proveedor: ' . $supplier->firstlast_name_social_reason); 
        return 1;                            
    }

    public function destroy($id){
        $supplier = Supplier::where('id', $id)->First();

        $supplier->is_active = 0;
        $supplier->is_deleted = 1;
        $supplier->update();

        return 1;

    }

}
