<?php

namespace App\Http\Controllers;

use App\{TaxDocument, BranchOffice, Company, EntityMasterdata, CorrelativeDocument, Dispatcher, TaxDocumentLine, Invoice, Product};
use Illuminate\Http\Request;
use DateTime;
use DateTimeZone;
use DataTables;
use DB;

class RemissionController extends Controller
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

    public function indexDt($id){      
      $arr = [
        ['entity_id', 8],
        ['code', '06']
      ];

      $docType = EntityMasterdata::where($arr)->First();      

      $arr = [
          ['tax_document.branch_office_id', $id],
          ['tax_document.is_processed', 1],
          ['document_type_id', $docType->id]      
      ];      

      //$creditNotes = TaxDocument::select("tax_document.*")->where($arr)->orderBy('tax_document.principal_code', 'desc');     
      $remission = DB::table('tax_document')
      ->join('invoice', 'tax_document.invoice_id', '=', 'invoice.id')
      ->join('client', 'invoice.client_id', '=', 'client.id')
      ->where($arr)
      ->select([
                'tax_document.id',
                'tax_document.principal_code',
                'tax_document.auth_code',
                'tax_document.emission_date',
                'tax_document.auth_date',                
                'client.social_reason',
                'client.identification_number'
        ]);                  

      return DataTables::query($remission)->make(true);
    }

    public function incrementCorrelative($id){
      $arr = [
        ['entity_id', 8],
        ['code', '06']
      ];

      $docTypeInv = EntityMasterdata::where($arr)->First();

      $arr = [
        ['company_id', $id],
        ['document_type_id', $docTypeInv->id]
      ];

      $correlative = CorrelativeDocument::where($arr)->First();
      $correlative->increment_number++;
      $correlative->save();
      return 1;
    }

    public function getRemissionXML($id){
      $remission = TaxDocument::where('id', $id)->select(['xml_generated', 'auth_code'])->First();
      return $remission;
    }

    public function getRemission($id) {
        

        $record = TaxDocument::with('documentLine', 'dispatcher', 'branch')->select("tax_document.*")->where('tax_document.id', $id)->First();        

        $environment = $record->branch->environment_type; 
        $emission    = $record->branch->emission_type; 
        // $retentionLine = TaxDocumentLine::where('tax_document_id', $retention->id)->get();
        // return $retentionLine;        

        $environment_type = EntityMasterdata::where('id', $environment)->First();        
        $emission_type    = EntityMasterdata::where('id', $emission)->First();
        $document_type    = EntityMasterdata::where('id', $record->document_type_id)->First();
        
        $date       = date_create($record->emission_date);
        $dateFormat = date_format($date, "d/m/Y");

        $arr = [];
        $arr['id']                      = $record->id;
        $arr['principal_code']          = $record->principal_code;
        $arr['referral_code']           = $record->referral_code;
        $arr['emission_date']           = $dateFormat;
        $arr['amount']                  = $record->amount;
        $arr['concept']                 = $record->concept;
        $arr['xml_generated']           = $record->xml_generated;
        $arr['auth_code']               = $record->auth_code;
        $arr['auth_date']               = $record->auth_date;
        $arr['emission_type']           = $emission_type->code;
        $arr['environment_type']        = $environment_type->code;
        $arr['invoice_id']              = $record->invoice_id;
        $arr['document_type_id']        = $document_type->code;     
        $arr['dispatcher_id']           = $record->dispatcher_id;
        $arr['car_register']            = $record->car_register;
        $arr['starting_point']          = $record->starting_point;

        $date       = date_create($record->startdate_transport);
        $dateFormat = date_format($date, "d/m/Y");
        $arr['startdate_transport']     = $dateFormat;

        $date       = date_create($record->enddate_transport);
        $dateFormat = date_format($date, "d/m/Y");
        $arr['enddate_transport']       = $dateFormat;
        
        $arr['destination_transport']   = $record->destination_transport;
        $arr['reason_transport']        = $record->reason_transport;
        $arr['custom_document']         = $record->custom_document;
        $arr['destination_branch_code'] = $record->destination_branch_code;
        $arr['route']                   = $record->route;

        $documentLine = $record->documentLine;        
        $dispatcher   = $record->dispatcher;          
        $branch       = $record->branch; 

        $invoice = Invoice::with('invoiceLine', 'client')->where('id', $record->invoice_id)->First();

        $date       = date_create($invoice->invoice_date);
        $dateFormat = date_format($date, "d/m/Y");
        $arr['modifiedDocumentEmissionDate'] = $dateFormat;

        $company = Company::where('id', $branch->company_id)->First();

        $auxCompany = [];      
        $auxCompany['id']              = $company->id;
        $auxCompany['social_reason']   = $company->name;
        $auxCompany['comercial_name']  = $company->comercial_name;
        $auxCompany['ruc']             = $company->ruc;
        $auxCompany['emission_code']   = $company->emission_code;
        $auxCompany['address']         = $company->address;
        $environment = EntityMasterdata::where('id', $company->environment_type)->First();      
        $auxCompany['environment']     = $environment->code;
        $auxCompany['is_accounting']   = $company->is_accounting;
        $auxCompany['is_artisan']      = $company->is_artisan;
        $auxCompany['register_number'] = $company->register_number;
        $auxCompany['special_code']    = $company->special_code;
        $auxCompany['email']           = $company->email; 
        $auxCompany['logo']            = $company->logo;
        $auxCompany['certP']           = $company->digital_certificate_pass;

        $auxBranch = [];      
        $auxBranch['id']             = $branch->id;
        $auxBranch['social_reason']  = $branch->name;
        $auxBranch['comercial_name'] = $branch->comercial_name;
        $auxBranch['ruc']            = $branch->ruc;
        $auxBranch['emission_point'] = $branch->emission_point;
        $auxBranch['address']        = $branch->address;                
        $auxBranch['email']          = $branch->email; 
        $auxBranch['logo']           = $branch->logo;  

        //$arr['document_line'] = $documentLine;
        $arr['company']       = $auxCompany;
        $arr['branch']        = $auxBranch;
        $arr['dispatcher']    = $dispatcher;                             

        $retentionLines = [];
        foreach ($documentLine as $line) {
            $referral_document_type = EntityMasterdata::where('id', $line->referral_document_type)->First();
            $tax_type_code          = EntityMasterdata::where('id', $line->tax_type_code)->First();
            if($line->retention_type_code != NULL){
                $retention_type_code    = EntityMasterdata::where('id', $line->retention_type_code)->First()->code;
            }
            else{
                $retention_type_code = $line->retention_type_code_alt;
            }


            $date       = date_create($line->doc_emission_date);
            $dateFormat = date_format($date, "d/m/Y");

            $auxLine = [];
            $auxLine['id'] = $line->id;            
            $auxLine['referral_document_type'] = $referral_document_type->code;
            $auxLine['document']               = strtoupper($referral_document_type->name);
            $auxLine['referral_document']      = $line->referral_document;
            $auxLine['doc_emission_date']      = $dateFormat;
            $auxLine['tax_type_code']          = $tax_type_code->code;
            $auxLine['retention_type_code']    = $retention_type_code;
            $auxLine['tax_base_amount']        = $line->tax_base_amount;
            $auxLine['tax_percentage']         = $line->tax_percentage;
            $auxLine['tax_total_amount']       = $line->tax_total_amount;
            $auxLine['tax_name']               = $tax_type_code->name;
            array_push($retentionLines, $auxLine);
        }        
        
        $idDocType = EntityMasterdata::where('id', $dispatcher['identification_type_id'])->First();

        $auxDispatcher = [];        
        $auxDispatcher['idClass']               = $idDocType->name;
        $auxDispatcher['identificationType']    = $idDocType->code;
        $auxDispatcher['identification_number'] = $dispatcher->identification_number;
        $auxDispatcher['identification']        = $dispatcher->identification_number;
        $auxDispatcher['social_reason']         = $dispatcher->social_reason;      
        $auxDispatcher['phone']                 = $dispatcher->phone;
        $auxDispatcher['address']               = $dispatcher->address;
        $auxDispatcher['email']                 = $dispatcher->email;        

        $arr['document_line'] = $retentionLines;
        $arr['dispatcher']    = $auxDispatcher;

        $arr['invoice'] = $invoice;
        
        $client = $invoice->client;
        $entity = EntityMasterdata::where('id', $client->identification_type_id)->First();
        $auxClient = [];
        $auxClient["social_reason"]       = $client->social_reason;
        $auxClient['comercial_name']      = $client->comercial_name;
        $auxClient['phone']               = $client->phone;
        $auxClient['address']             = $client->address;
        $auxClient['email']               = $client->email;
        $auxClient['identificationType']  = $entity->code;
        $auxClient['identification']      = $client->identification_number;
        $auxClient['idClass']             = $entity->name;

        $arr['client'] = $auxClient;

        $productLines = [];
        foreach ($invoice->invoiceLine as $line) {        
          $auxLine = [];
          $product    = Product::with('producttax')->where('product.id', $line->product_id)->First();            
          $auxLine['principal_code'] = $product->principal_code;
          $auxLine['auxiliary_code'] = $product->auxiliary_code;        
          $auxLine['description']    = $product->description;                
          $auxLine['quantity']       = $line->quantity;                
          array_push($productLines, $auxLine);
        }

        $arr['invoiceLine'] = $productLines;

        return $arr;        
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
        // $supplier         = Supplier::where('id', $request['supplier_id'])->First();

        $arr = [
          ['entity_id', 8],
          ['code', '06']
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
        $documentType = "06"; // Guia de Remisión
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
            'dispatcher_id'       => $request['dispatcher_id'],
            'car_register'        => $request['car_register'],
            'starting_point'      => $request['starting_point'],
            'startdate_transport' => $request['startdate_transport'],
            'enddate_transport'   => $request['enddate_transport'],
            'destination_transport' => $request['destination_transport'],
            'reason_transport'    => $request['reason_transport'],
            'custom_document'     => $request['custom_document'],
            'route'               => $request['route'],
            'destination_branch_code' => $request['destination_branch_code'],
            'invoice_id'          => $request['invoice_id'],
            'is_processed'        => $request['is_processed'],
        ]);
                
        foreach ($request['taxDocumentLine'] as $line) 
        {
           $aux = json_decode($line);                            

           $taxDocumentLine = TaxDocumentLine::create([ 
              'tax_document_id'        => $taxdocument->id,
              'referral_document_type' => $aux->referral_document_type,
              'referral_document'      => $aux->referral_document,
              'doc_emission_date'      => $aux->doc_emission_date,
              'tax_type_code'          => $aux->tax_type_code,
              'tax_base_amount'        => $aux->tax_base_amount,
              'tax_percentage'         => $aux->tax_percentage,
              'tax_total_amount'       => $aux->tax_total_amount,
              'product_id'             => $aux->product_id,
              'quantity'               => $aux->quantity
           ]);
        }

          
        //}    

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
        $taxes = TaxDocument::with('company', 'emissiontype', 'environmenttype', 'dispatcher', 'document_type')->select("taxdocument.*")->where($arr)->First();
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
        $taxes = TaxDocument::with('company', 'emissiontype', 'environmenttype', 'dispatcher', 'document_type')->select("taxdocument.*")->where($arr)->First();
    }

    public function xmlAuthorized(Request $request, $id){
        $record = TaxDocument::where('id', $id)->First();            
        $record->auth_date      = $request['auth_date'];
        $record->xml_generated  = $request['xml']; 
        $record->is_processed   = 1;      
        $record->save();
        return 1;
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
