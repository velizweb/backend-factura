<?php

namespace App\Http\Controllers;

use App\{MailConfiguration, Company, EntityMasterdata};
use DataTables; 
use Illuminate\Http\Request;

class MailConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entities = MailConfiguration::All();
        return $entities;
    }

    public function indexDtAll() {        
      $arr = [
         ['is_active', '1'],
          ['is_deleted', '0']
      ];

      $mailConfig = MailConfiguration::with('company', 'mailservertype', 'securitytype', 'identificationtype')->select("mail_configuration.*")->where($arr)->get();  
      
      if(count($mailConfig) > 0)
          foreach ($mailConfig as $element) {
            $element->company->logo = null;
            $element->company->digital_certificate = null;
          }

      return DataTables::collection($mailConfig)->make(true);
    }

    public function indexDt($id) {        
        $arr = [
            ['is_active', '1'],
            ['is_deleted', '0'],
            ['company_id', $id]
        ];
        
        $mailConfig = MailConfiguration::with('company', 'mailservertype', 'securitytype', 'identificationtype')->select("mail_configuration.*")->where($arr)->get();  

        if(count($mailConfig) > 0){
            $mailConfig[0]->company->logo = null;
            $mailConfig[0]->company->digital_certificate = null;
        }

        return DataTables::collection($mailConfig)->make(true);
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
        MailConfiguration::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $arr = [
          ['id', $id]
        ];
        $entity = MailConfiguration::with('company', 'mailservertype', 'securitytype', 'identificationtype')->select("mail_configuration.*")->where($arr)->first();
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
        $arr = [
          ['id', $id]
        ];
        $entity = MailConfiguration::with('company', 'mailservertype', 'securitytype', 'identificationtype')->select("mail_configuration.*")->where($arr)->first();
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
        MailConfiguration::findOrFail($id)->update($request->All());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MailConfiguration::findOrFail($id)->delete();
    }
}
