<?php

namespace App\Http\Controllers;

use App\{TaxDocument, BranchOffice, Company, EntityMasterdata, CorrelativeDocument, Supplier, TaxDocumentLine};
use Illuminate\Http\Request;
use DateTime;
use DateTimeZone;
use DataTables;

class TaxDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entities = TaxDocument::All();
        return $entities;
    }

    public function indexDt($id)
    {
        $arr = [
            ['id', $id],
            ['is_active', 1],
            ['is_deleted', 0]
        ];  
        $taxes = TaxDocument::with('company', 'emissiontype', 'environmenttype', 'supplier', 'document_type')->select("taxdocument.*")->where($arr);

        return DataTables::eloquent($taxes)->make(true);
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
     public function store(Request $request) {            
        $branch           = BranchOffice::where('id', $request['branch_office_id'])->First();         
        $company          = Company::where('id', $branch->company_id)->First();
        $emission_type    = EntityMasterdata::where('id', $branch->emission_type)->First()->code;
        $environment_type = EntityMasterdata::where('id', $branch->environment_type)->First()->code;          
        $supplier         = Supplier::where('id', $request['supplier_id'])->First();

        $arr = [
          ['entity_id', 8],
          ['code', '07']
        ];

        $docTypeInv = EntityMasterdata::where($arr)->First();

        $arr = [
          ['company_id', $company->id],
          ['document_type_id', $docTypeInv->id]
        ];               
        
        $correlative = CorrelativeDocument::where($arr)->First();        
        $invNum = $correlative->increment_number;        
        
        $stringAux = "" . $invNum;
        $stringCorrelative = str_pad($stringAux, 9, "0", STR_PAD_LEFT);

        $serie = str_pad($company->emission_code . $branch->emission_point, 6, "0", STR_PAD_LEFT);
        $invNumber = '' . $serie . $stringCorrelative;                

        $date = new DateTime("now", new DateTimeZone('America/Guayaquil') );
        $emissionDate = $date->format('Y-m-d');                
        $day    = $date->format('d');
        $month  = $date->format('m');
        $year   = $date->format('Y');

        // $emissionDate = date('Y-m-d');
        // $day    = date('d');
        // $month  = date('m');
        // $year   = date('Y');
        $documentType = "07"; // Retenciones
        $ruc = str_pad($company->ruc, 13, "0", STR_PAD_LEFT);
        
        $numericField = str_pad(rand(1,77777), 8, "0", STR_PAD_LEFT);
        $auth_code = "" . $day . $month . $year . $documentType . $ruc . $environment_type . $invNumber . $numericField . $emission_type;

        $codeArr = str_split($auth_code);
        $module11 = 7;
        $numArr = [];
        for ($i=0; $i < 48; $i++) { 
          
          if($module11 == 1) 
            $module11 = 7;
          
          $numArr[$i] = $module11;
          $module11--;
        }

        $acum = 0;
        $auxNumber = 0;
        for ($i=0; $i < 48; $i++) { 
          $auxNumber = (int)$codeArr[$i] * $numArr[$i];
          $acum += $auxNumber;
        }

        $module         = $acum % 11;        
        $verDigit       = 11 - $module;

        if($verDigit > 9){
          //cuando el dígito verificador sea un número con dos cifras, se tomará sólo la primera
          //caso de ejemplo 020420180117141474910011001001000000066000002221
          //la cifra acumulada tiene un valor de 364
          //364%11 = 1; 
          //11 - 1 = 10; el dígito verificador es un número con 2 cifras
          $auxDigit = "" . $verDigit;
          $verDigit = substr($auxDigit, 0, 1);          
        }

        $auth_code = $auth_code . $verDigit;        

        $taxdocument = TaxDocument::create([
            'principal_code'      => $stringCorrelative,
            'referral_code'       => $invNumber,
            'emission_date'       => $emissionDate,            
            'amount'              => $request['amount'],
            'concept'             => $request['concept'],             
            'auth_code'           => $auth_code,
            'emission_type'       => $emission_type,
            'environment_type'    => $environment_type,
            'document_type_id'    => $docTypeInv->id,
            'branch_office_id'    => $branch->id,
            'supplier_id'         => $supplier->id,
            'is_processed'        => $request['is_processed'],
        ]);
                
        foreach ($request['taxDocumentLine'] as $line) {
          $aux = json_decode($line);                            

          $retentiontypecode = "";

          if(isset($aux->retentiontypecode->tax_percentage_id)){
              $retentiontypecode = $aux->retentiontypecode->tax_percentage_id;
              $taxDocumentLine = TaxDocumentLine::create([ 
                'tax_document_id'        => $taxdocument->id,
                'referral_document_type' => $aux->referral_document_type,
                'referral_document'      => $aux->referral_document,
                'doc_emission_date'      => $aux->doc_emission_date,
                'tax_type_code'          => $aux->taxtypecode->id,
                'retention_type_code'    => $retentiontypecode,
                'tax_base_amount'        => $aux->tax_base_amount,
                'tax_percentage'         => $aux->tax_percentage,
                'tax_total_amount'       => $aux->tax_total_amount
              ]);
            }
          else{
              $retentiontypecode = $aux->retentiontypecode->tax_alt;
              $taxDocumentLine = TaxDocumentLine::create([ 
                'tax_document_id'         => $taxdocument->id,
                'referral_document_type'  => $aux->referral_document_type,
                'referral_document'       => $aux->referral_document,
                'doc_emission_date'       => $aux->doc_emission_date,
                'tax_type_code'           => $aux->taxtypecode->id,
                'retention_type_code_alt' => $retentiontypecode,
                'tax_base_amount'         => $aux->tax_base_amount,
                'tax_percentage'          => $aux->tax_percentage,
                'tax_total_amount'        => $aux->tax_total_amount
              ]);
          }

          
        }    

        return $taxdocument->id;
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
        $arr = [
            ['id', $id],
            ['is_active', 1],
            ['is_deleted', 0]
        ];  
        $taxes = TaxDocument::with('company', 'emissiontype', 'environmenttype', 'supplier', 'document_type')->select("taxdocument.*")->where($arr)->First();
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
            ['id', $id],
            ['is_active', 1],
            ['is_deleted', 0]
        ];  
        $taxes = TaxDocument::with('company', 'emissiontype', 'environmenttype', 'supplier', 'document_type')->select("taxdocument.*")->where($arr)->First();
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
        TaxDocument::findOrFail($id)->update($request->All());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TaxDocument::findOrFail($id)->delete();
    }
}
