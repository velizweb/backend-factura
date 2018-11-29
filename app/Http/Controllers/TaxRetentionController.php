<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AuditHelper;
use DataTables;
use App\{TaxDocument, TaxDocumentLine, EntityMasterdata, Company, CorrelativeDocument};

class TaxRetentionController extends Controller
{

    public function __construct(){
       //$this->middleware('jwt.auth');
    }

    public function indexDt($id){
      $arr = [
        ['entity_id', 8],
        ['code', '07']
      ];

      $docType = EntityMasterdata::where($arr)->First();

      $arr = [
          ['tax_document.branch_office_id', $id],
          ['tax_document.is_processed', 1],
          ['document_type_id', $docType->id]      
      ];


      $retentions = TaxDocument::with('supplier')->select("tax_document.*")->where($arr)->orderBy('tax_document.principal_code', 'desc');     

      //AudioHelper::Audit($branchOffice->company_id, 'Listar Facturas de la Sucursal: ' . $branchOffice->name . ', Cliente: ' . $client->firstlast_name_social_reason);

      return DataTables::eloquent($retentions)->make(true);
    }

    public function incrementCorrelative($id){
      $arr = [
        ['entity_id', 8],
        ['code', '07']
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

    public function getRetentionXML($id){
      $retention = TaxDocument::where('id', $id)->select(['xml_generated', 'auth_code'])->First();
      return $retention;
    }

    public function getRetention($id) {
      
        $retention = TaxDocument::with('documentLine', 'supplier', 'branch')->select("tax_document.*")->where('tax_document.id', $id)->First();

        $environment = $retention->branch->environment_type; 
        $emission    = $retention->branch->emission_type; 
        // $retentionLine = TaxDocumentLine::where('tax_document_id', $retention->id)->get();
        // return $retentionLine;        

        $environment_type = EntityMasterdata::where('id', $environment)->First();        
        $emission_type    = EntityMasterdata::where('id', $emission)->First();
        $document_type    = EntityMasterdata::where('id', $retention->document_type_id)->First();
        
        $date       = date_create($retention->emission_date);
        $dateFormat = date_format($date, "d/m/Y");

        $arr = [];
        $arr['id']               = $retention->id;
        $arr['principal_code']   = $retention->principal_code;
        $arr['referral_code']    = $retention->referral_code;
        $arr['emission_date']    = $dateFormat;
        $arr['amount']           = $retention->amount;
        $arr['concept']          = $retention->concept;
        $arr['xml_generated']    = $retention->xml_generated;
        $arr['auth_code']        = $retention->auth_code;
        $arr['auth_date']        = $retention->auth_date;
        $arr['emission_type']    = $emission_type->code;
        $arr['environment_type'] = $environment_type->code;
        $arr['invoice_id']       = $retention->invoice_id;
        $arr['document_type_id'] = $document_type->code;        

        $documentLine = $retention->documentLine;        
        $supplier     = $retention->supplier;        
        $branch       = $retention->branch; 

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
        $arr['supplier']      = $supplier;                             

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

        $idDocType = EntityMasterdata::where('id', $supplier->identification_type_id)->First();
        $auxSupplier = [];        
        $auxSupplier['idClass']               = $idDocType->name;
        $auxSupplier['identificationType']    = $idDocType->code;
        $auxSupplier['identification_number'] = $supplier->identification_number;
        $auxSupplier['identification']        = $supplier->identification_number;
        $auxSupplier['social_reason']         = $supplier->social_reason;
        $auxSupplier['comercial_name']        = $supplier->comercial_name;
        $auxSupplier['phone']                 = $supplier->phone;
        $auxSupplier['address']               = $supplier->address;
        $auxSupplier['email']                 = $supplier->email;
        $auxSupplier['tax_period']            = substr($supplier->tax_period, 0, 2) . "/" . substr($supplier->tax_period, 2);

        $arr['document_line'] = $retentionLines;
        $arr['supplier']      = $auxSupplier;
        return $arr;        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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

    public function xmlAuthorized(Request $request, $id){
        $retention = TaxDocument::where('id', $id)->First();            
        $retention->auth_date      = $request['auth_date'];
        $retention->xml_generated  = $request['xml']; 
        $retention->is_processed   = 1;      
        $retention->save();
        return 1;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
      $retention = TaxDocument::where('id', $id)->First();
      if(count($retention) > 0){              
          $retention->is_deleted = 1;
          $retention->save();
          return 1;        
      }
      return 0;    
    }
}
