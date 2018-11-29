<?php

namespace App\Http\Controllers;

use App\ProductTax;

use Illuminate\Http\Request;

class ProductTaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request["producttax"] as $producttax) {
            $aux = json_decode($producttax);
            ProductTax::create([
                'product_id' => $aux->product_id,
                'country_tax_id'=> $aux->country_tax_id,
                'is_default' => $aux->is_default
            ]);

        }
        return response("", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $taxes = ProductTax::with('countrytax')->select("product_tax.*")->where('product_id', $id)->get();

        return $taxes;
    }

    public function default($id)
    {
        $arr = [
            ['product_id', '=', $id],
            ['is_default', '=', 1]            
        ];
        $tax = ProductTax::with('countrytax')->select("product_tax.*")->where($arr)->first();
        return $tax;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //return $request->all();
        $temp = $request['producttax[]'];

        foreach ($temp as $producttax) {
            $aux = $producttax;
            if(!$aux['id']>0){
                ProductTax::create([
                    'product_id' => $aux['product_id'],
                    'country_tax_id'=> $aux['country_tax_id'],
                    'is_default' => $aux['is_default']
                ]);
            }else{
                $producto = ProductTax::findOrFail($aux['id']);
                $producto->country_tax_id = $aux['country_tax_id'];
                $producto->is_default = $aux['is_default'];
                $producto->update();
            }
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $producttax = ProductTax::findOrFail($id)->delete();
        //
    }
}
