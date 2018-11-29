<?php

namespace App\Http\Controllers;

use App\CorrelativeDocument;
use Illuminate\Http\Request;

class CorrelativeDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 1;

        //
    }

    public function indexByCompany($IdCompany)
    {
        $documents = CorrelativeDocument::with('documenttype')->select("correlative_document.*")->where('company_id', $IdCompany)->get();
        return $documents;
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
        foreach ($request["correlativo"] as $correlativo) {
            $aux = json_decode($correlativo);
            //return $request->all();
            CorrelativeDocument::create([
                'increment_number' => $aux->increment_number,
                'serie'=> $aux->serie,
                'company_id' => $aux->company_id,
                'document_type_id' => $aux->document_type_id
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
        //
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
        foreach ($request["correlativo[]"] as $correlativo) {
            //return $correlativo;

            //$aux = json_decode($correlativo);
            $record = CorrelativeDocument::findOrfail($correlativo['id']);
            $record->serie            = $correlativo['serie'];
            $record->increment_number = $correlativo['increment_number'];

            $record->update();
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
        //
    }
}
