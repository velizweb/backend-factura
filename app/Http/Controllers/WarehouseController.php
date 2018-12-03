<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Warehouse;
use App\WarehouseProduct;
use App\Product;
use App\ProductMovement;
use DataTables;
use App\Helpers\AuditHelper;
use JWTAuth;

class WarehouseController extends Controller
{
    public function __construct(){
       //$this->middleware('jwt.auth');
    }

    public function index(){
       $arr = [
            ['is_deleted', '<>', 1]            
        ];         
       $warehouse = Warehouse::where($arr)->get(); 
       return $warehouse; 
    }

    public function indexDt() {
        $arr = [
            ['is_deleted', '<>', 1]            
        ];        
        $warehouse = Warehouse::where($arr)->get();            
        //AuditHelper::Audit($user->company_id, 'listar planes'); 
        return DataTables::collection($warehouse)->make(true);       
        //return $plans;
    }

    public function show($id){
        $entity = Warehouse::findOrFail($id);
        return $entity;
    }

    public function store(Request $request){

        $warehouse = Warehouse::create($request->all());

        return 1;

    }

    public function edit($id){
        $entity = Warehouse::findOrFail($id);
        return $entity;
    }

    public function update(Request $request, $id) {
        //$user = JWTAuth::parseToken()->authenticate();

        $warehouse = Warehouse::findOrFail($id);

        $warehouse->update($request->all());

        //AuditHelper::Audit($user->company_id, 'editar plan: ' . $plan->name);

        return 1;                            
    }

    public function destroy($id) { 
        //$user = JWTAuth::parseToken()->authenticate();
        $warehouse = Warehouse::findOrFail($id); 
        //AuditHelper::Audit($user->company_id, 'Eliminar plan: ' . $plan->name);
        $warehouse->is_active = 0;
        $warehouse->is_deleted = 1;
        $warehouse->update();
        //$plan->delete();               
        return 1;
    }

    public function products($warehouse, $company, $branch)
    {
        $arr = [
            ['company_id', $company],
            ['branch_id', $branch],
            ['warehouse_id', $warehouse]
        ];


        $product = WarehouseProduct::where($arr)
                    ->with('product')
                    ->get();

        return $product->pluck('product');
    }

    public function storeEntryProduct(Request $request)
    {
        $company = $request->get('company');
        $branch  = $request->get('branch');
        $warehouse = $request->get('warehouse');
        $products = $request->get('products');
        $audit = $request->get('audit');
        $invoiceNumber = $request->get('invoiceNumber');
        $observation = $request->get('observation');
        $type = $request->get('type');

        foreach ($products as $pro) {
            $pro = json_decode($pro);
        
            $cond = [
                ['company_id', $company],
                ['branch_id', $branch],
                ['warehouse_id', $warehouse],
                ['product_id', $pro->product->product_id]
            ];

            
            $product = WarehouseProduct::where($cond)->first();

            if ($product) {
                $target_amount = $product->quantity;
                $product->quantity = $product->quantity + $pro->transferAmount;
            } else {
                $target_amount = $pro->currentAmount;

                $product = new WarehouseProduct;
                $product->company_id = $company;
                $product->branch_id = $branch;
                $product->warehouse_id = $warehouse;
                $product->product_id = $pro->product->product_id;
                $product->quantity = $pro->transferAmount;
            }
            
            $product->save();

            /**
             * Get Product by Audit o Update Precio Product
             */
            
            if ($audit == 1) {
                $pd = Product::findOrFail($pro->product->product_id);
            } else {
                Product::where('id', $pro->product->product_id)
                    ->update([
                        "unit_price" => $pro->product->invoiceAmount/$product->quantity,
                        "unit_cost" => $pro->product->invoiceAmount/$product->quantity
                    ]);
            }
            
            /**
             * Creation of data for the movement record of the product
             * @var ProductMovements $pm
             */

            ProductMovement::create([
                "company_id" => $company,
                "branch_origin" => $branch,
                "warehouse_origin" => $warehouse,
                "destination_branch" => $branch,
                "destination_store" => $warehouse,
                "product_id" => $pro->product->product_id,
                "quantity_origin" => $pro->currentAmount,
                "target_amount" => $target_amount,
                "amount_send" => $pro->transferAmount,
                "current_origin_quantity" => $product->quantity,
                "current_destination_quantity" => $product->quantity,
                "supplier"  => ($audit == 1) ? 0 : $pro->product->supplier_id,
                "invoiceNumber" => ($audit == 1) ? 'EBA-'.date('Ymd') : $invoiceNumber,
                "invoiceAmount" => ($audit == 1) ? $pd->unit_price * $pro->transferAmount : $pro->product->invoiceAmount,
                "observation"   => $observation,
                "expiration_date" => ($audit == 1) ? date("Y-m-d H:i:s") : date("Y-m-d H:i:s", strtotime($pro->product->expiration_date)),
                "types" => $type
            ]);
        }
        return 1;
    }

    public function storeEntryTransfer(Request $request)
    {
        $company     = $request->get('company');
        $origin      = $request->get('origin');
        $destination = $request->get('destination');
        $doc_num     = $request->get('document_num');
        $products    = $request->get('products');
        $branch_origin = $request->get('branch_origin');
        $destination_branch = $request->get('destination_branch');

        foreach ($products as $pro) {
            $pro = json_decode($pro);
            
            $condOrigin = [
                ['company_id', $company],
                ['branch_id', $branch_origin],
                ['warehouse_id', $origin],
                ['product_id', $pro->product->product_id]
            ];

            $wo = WarehouseProduct::where($condOrigin)->first();
            $wo->quantity = $pro->currentAmount - $pro->transferAmount;
            $wo->save();

            /**
             * Creation of data for the movement record of the product
             * @var ProductMovements $pm
             */
            
            ProductMovement::create([
                "company_id" => $company,
                "branch_origin" => $branch_origin,
                "warehouse_origin" => $origin,
                "destination_branch" => $destination_branch,
                "destination_store" => $destination,
                "product_id" => $pro->product->product_id,
                "quantity_origin" => $pro->currentAmount,
                "target_amount" => 0,
                "amount_send" => $pro->transferAmount,
                "current_origin_quantity" => $wo->quantity,
                "current_destination_quantity" => 0,
                "document_number" => $doc_num,
                "types" => 7 
            ]);
            
        }

        return 1;
    }

    public function storeEntryOutput(Request $request)
    {
        $cond = [
            ['company_id', $request->get('company')],
            ['branch_id', $request->get('branch')],
            ['warehouse_id', $request->get('warehouse')],
            ['product_id', $request->get('product')]
        ];

        $wp = WarehouseProduct::where($cond)->first();

        $previousAmount = $wp->quantity;

        $wp->quantity = $wp->quantity - $request->get('quantity');

        $wp->save();    
        
        $product = Product::where('id',$request->get('product'))
                ->select('unit_price')
                ->first(); 

        /* Creation of data for the movement record of the product
         * @var ProductMovements
         */
        
        ProductMovement::create([
            "company_id" => $request->get('company'),
            "branch_origin" => $request->get('branch'),
            "warehouse_origin" => $request->get('warehouse'),
            "destination_branch" => $request->get('branch'),
            "destination_store" => $request->get('warehouse'),
            "product_id" => $request->get('product'),
            "quantity_origin" => $previousAmount,
            "amount_send" => $request->get('quantity'),
            "current_origin_quantity" => $wp->quantity,
            "invoiceAmount" => $request->get('quantity') * $product->unit_price,
            "observation" => $request->get('observation'),
            "types" => $request->get('type')
        ]);

        return 1;
    }

    public function getWarehouseProduct($company, $warehouse, $product, $branch)
    {
        $cond = [
            ['company_id', $company],
            ['branch_id', $branch],
            ['warehouse_id', $warehouse],
            ['product_id', $product]
        ];

        $wp = WarehouseProduct::where($cond)->with('product')->first();

        return (empty($wp)) ? 0 : $wp;
    }

    public function getTransfer($doc_num)
    {
        return ProductMovement::where('document_number', $doc_num)
                                ->with('warehouse_origin', 'product')
                                ->get();
    }

    public function acceptTransfer($doc_num)
    {
        $pms = ProductMovement::where('document_number', $doc_num)
                                ->get();
        foreach ($pms as $pm) {
            $branch_destination = $pm->destination_branch;
            
            $cond = [
                    ['company_id', $pm->company_id],
                    ['branch_id', $pm->destination_branch],
                    ['warehouse_id', $pm->destination_store],
                    ['product_id', $pm->product_id]
                ];

            $wd = WarehouseProduct::where($cond)->first();

            if ($wd) {
                $previousAmount = $wd->quantity;
                $wd->quantity = $wd->quantity + $pm->amount_send;
            } else {
                $previousAmount = 0;
                $wd =  new WarehouseProduct;
                $wd->company_id = $pm->company_id;
                $wd->warehouse_id = $pm->destination_store;
                $wd->product_id = $pm->product_id;
                $wd->quantity = $pm->amount_send;   
                $wd->branch_id = $pm->destination_branch;
            } 
            
            $wd->save();

            $pm->types = 3;
            $pm->target_amount = $previousAmount;
            $pm->current_destination_quantity = $wd->quantity;
            $pm->reception_date = date('Y-m-d H:i:s');

            $pm->save(); 
        }
        return 1;
    }

    public function warehouseTransfers($company, $warehouse)
    {
        $cond = [
            ['company_id', $company],
            ['warehouse_id', $warehouse]
        ];   

        return WarehouseProduct::where($cond)->with('product')->get();
    }

    public function indexMov($type)
    {
        if ($type == 'output') {
            $data = ProductMovement::whereIn('types', [2,4,5,6,9])->with('warehouse_origin', 'destination_store', 'product','supplier')->get();
        } else{
            $cond = ($type == 1) ? [1, 8] : [$type];
            $data = ProductMovement::whereIn('types', $cond)->with('warehouse_origin', 'destination_store', 'product','supplier')->get();
        }
        

        return DataTables::collection($data)->make(true); 
    }
}
