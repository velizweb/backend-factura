<?php

namespace App\Http\Controllers;
use App\{Product, AuditUser};
use DataTables;
use JWTAuth;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(){
       #$this->middleware('jwt.auth');
    }

    public function index() {
        $arr = [
            //['company_id', $id],
            ['is_active', 1],
            ['is_deleted', 0]
        ];        
        $products = Product::where($arr)->get();
        //AuditHelper::Audit($request['company'], 'listar productos');        
        return $products;
    }

    public function indexFilter($id) {
        $arr = [
            ['company_id', $id],
            ['is_active', 1],
            ['is_deleted', 0]
        ];        
        $products = Product::where($arr)->get();
        //AuditHelper::Audit($request['company'], 'listar productos');        
        return $products;
    }    

    public function indexDt($id) {
        $arr = [
            ['company_id', $id],
            ['is_active', 1],
            ['is_deleted', 0]
        ];        
        $products = Product::where($arr);
        //AuditHelper::Audit($request['company'], 'listar productos');        
        return DataTables::eloquent($products)->make(true);
    }

    public function show($id) {        
        $product = Product::where('id', $id)->with('warehouseProduct')->First();
        //AuditHelper::Audit($request['company'], 'ver producto: ' . $product->name);
        return $product;
    }

    public function import(Request $request){

        //return $request["products"];
        
        foreach ($request["products"] as $product) {
            //$product = json_decode($product);
            return $product->name;
            //return $product;
            //Product::create($aux->all());
            Product::create([
                'name'               => $product->name,
                'principal_code'     => $product->principal_code,
                'auxiliary_code'     => $product->auxiliary_code,
                'description'        => $product->description,
                'generic'            => $product->generic,
                'unit_price'         => $product->unit_price,
                'unit_cost'          => $product->unit_cost,
                'is_purchase_active' => $product->is_purchase_active,
                'is_sale_active'     => $product->is_sale_active,
                'min_stock'          => $product->min_stock,
                'max_stock'          => $product->max_stock,
                'company_id'         => $product->company_id,
                'is_active'          => $product->is_active,
                'is_deleted'         => $product->is_deleted 
            ]);
        }
    }

    public function store(Request $request) {
        //$user = JWTAuth::parseToken()->authenticate();

        $product = Product::create($request->all());    

        //AuditHelper::Audit($user->company_id, 'crear producto: ' . $product->name);
        
        return $product;
    }

    public function update(Request $request, $id) {
        //$user = JWTAuth::parseToken()->authenticate();         

        $product = Product::where('id', $id)->First()->update($request->all());                

        //AuditHelper::Audit($user->company_id, 'editar producto: ' . $product->name);

        return 1;                            
    }

    public function destroy($id) { 
        $product = Product::where('id', $id)->First(); 
        //AuditHelper::Audit($product->company_id, 'Eliminar producto: ' . $product->name);
        $product->is_active     = 0;
        $product->is_deleted    = 1;    
        $product->save();                   
        return 1;
    }
}
