<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CountryTax;
use DataTables;
use App\EntityMasterdata;

class CountryTaxController extends Controller
{
    public function index($id)
    {

        $taxes = CountryTax::with('tax')->select("country_tax.*")
                    ->where('country_id',$id)
                    ->get();        

        return $taxes;
    }

    public function indexLista($id, $EntityTax)
    {
        $arr = [
            ['country_tax.country_id', $id]
        ]; 

        $arr2 = [
            ['entity_id', $EntityTax],
            ['is_active', 1]
        ]; 

        $taxids = EntityMasterdata::where($arr2)->get()->pluck("id");

        $taxes = CountryTax::with('tax', 'taxpercentage')
                    ->select("country_tax.*")
                    ->where($arr)
                    ->whereIn('country_tax.tax_id', $taxids)
                    ->get(); 

        $temporal = [];

        foreach ($taxes as $tax) {
            if($tax->tax->entity_id==$EntityTax) array_push($temporal, $tax);
        }

        return $temporal;

    }

    public function indexDebitNote($id, $EntityTax, $EntityTaxpercentage)
    {
        $arr = [
            ['country_tax.country_id', $id]
        ]; 

        $arr2 = [
            ['entity_id', $EntityTax],
            ['is_active', 1]
        ]; 

        $percentage = EntityMasterdata::where($arr2)->get()->pluck("id");

        $taxes = CountryTax::with('tax', 'taxpercentage')
                        ->select("country_tax.*")
                        ->where($arr)
                        ->whereIn('country_tax.tax_id', $percentage)
                        ->get(); 

        $temporal = [];

        foreach ($taxes as $tax) {
            if($tax->tax->entity_id==$EntityTax) array_push($temporal, $tax);
        }

        return $temporal;

    }

    public function indexTaxRetention($id, $idretention)
    {
        $taxes = EntityMasterdata::where("entity_id", $idretention)->get()->pluck("id");        

        $retention = CountryTax::with('tax', 'taxpercentage')
                        ->select("country_tax.*")
                        ->where('country_id',$id)
                        ->whereIn('tax_id', $taxes)
                        ->get();  
        

        return $retention;
    }

    public function indexTaxPercentage($id, $idTax)
    {
        $arr = [
            ['country_id', $id],
            ['tax_id', $idTax]          
        ];      

        $retention = CountryTax::with('tax', 'taxpercentage')
                        ->select("country_tax.*")
                        ->where($arr)
                        ->get();  
        

        return $retention;
    }

    public function indexDt($id=28){
        $taxes = CountryTax::with('tax', 'taxpercentage', 'country')->select("country_tax.*")->where('country_id', $id);        
        return DataTables::eloquent($taxes)->make(true);        
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // $tax = EntityMasterdata::create([
        //     'code'          => $request['code'],
        //     'name'          => $request['name'],
        //     'description'   => $request['description'],
        //     'is_active'     => $request['is_active'],
        //     'entity_id'     => 9
        // ]);

        //return $request;

        $taxCountry = CountryTax::create([
            'country_id'    => $request['country_id'],
            'tax_id'        => $request['tax_id'],
            'tax_percentage_id' => $request['tax_percentage_id'],
            'value'         => $request['value']         
        ]);

        return 1;
    }

    public function show($id)
    {
        $tax = CountryTax::with('tax', 'taxpercentage', 'country')->select("country_tax.*")->where('id',$id)->First();
        return $tax;
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $taxCountry = CountryTax::where('id', $id)->First();
        $taxCountry->country_id = $request['country_id'];  
        $taxCountry->tax_id = $request['tax_id']; 
        $taxCountry->tax_percentage_id = $request['tax_percentage_id']; 
        $taxCountry->value      = $request['value'];            
        $taxCountry->save();

        // $tax = EntityMasterdata::where('id', $taxCountry->tax_id)->First();
        // $tax->code          = $request['code'];
        // $tax->name          = $request['name'];
        // $tax->description   = $request['description'];
        // $tax->is_active     = $request['is_active'];                    
        // $tax->save();        

        return 1;
    }

    public function destroy($id)
    {
        //
    }
}
