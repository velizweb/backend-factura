<?php

namespace App\Http\Controllers;

use App\EntityMasterdata;
use App\Entity;
use Illuminate\Http\Request;
use DataTables;

class EntityMasterdataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $masterdata = EntityMasterdata::All();

        return $masterdata;
    }

    public function indexDt()
    {
        $arr = [
            ['is_deleted', '<>', 1]            
        ];

        $masterdata = EntityMasterdata::with('entity')->select("entity_masterdata.*");
        $masterdata->where($arr)->get();
        //AuditHelper::Audit($client->company_id, 'listar clientes');        
        return DataTables::eloquent($masterdata)->make(true);

    }    

    public function entitymasterdataByEntity($id){
        $arr = [
            ['entity_id', '=', $id],
            ['is_active', '=', 1]            
        ];

        $masterdata = EntityMasterdata::where($arr)->get();
        return $masterdata;
    }

    public function entitymasterdataByEntityDt($id){
        $arr = [
            ['entity_id', '=', $id],
            ['is_active', '=', 1]            
        ];

        $masterdata = EntityMasterdata::where($arr)->orderBy('code', 'ASC');
        return DataTables::eloquent($masterdata)->make(true);
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

    public function store(Request $request)
    {
        $masterdata = EntityMasterdata::create($request->all());
        return 1;

    }

    public function filter($id)
    {
        $retorno = EntityMasterdata::All()->Where('entity_id', $id);
        return $retorno;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id (Entity Id)
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $entity = EntityMasterdata::with('entity')->select("entity_masterdata.*")->where('id', $id)->first();
        return $entity;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $entity = EntityMasterdata::with('entity')->select("entity_masterdata.*")->where('id', $id)->first();
        return $entity;
        
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
        $entity = EntityMasterdata::findOrfail($id)->update($request->all());
        return 1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $entitymasterdata = EntityMasterdata::findOrFail($id);        

        //AuditHelper::Audit($branchOffice->company_id, 'eliminar sucursal: ' . $branchOffice->name);
        
        $entitymasterdata->is_active    = 0;
        $entitymasterdata->is_deleted   = 1;

        $entitymasterdata->update();   

        return 1;
    }
}
