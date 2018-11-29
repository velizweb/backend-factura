<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AuditHelper;
use DB;
use DateTime;
use DateTimeZone;
use DataTables;
use App\{TaxDocument,TaxDocumentLine, DebitNotePayment, EntityMasterdata, Company, BranchOffice, CorrelativeDocument, Invoice, Product, CountryTax};

class DebitNoteController extends Controller
{

    public function __construct(){
       //$this->middleware('jwt.auth');
    }

    public function indexDt($id){        
      $arr = [
        ['entity_id', 8],
        ['code', '05']
      ];

      $docType = EntityMasterdata::where($arr)->First();      

      $arr = [
          ['tax_document.branch_office_id', $id],
          ['tax_document.is_processed', 1],
          ['document_type_id', $docType->id]      
      ];      

      //$creditNotes = TaxDocument::select("tax_document.*")->where($arr)->orderBy('tax_document.principal_code', 'desc');     
      $debitNotes = DB::table('tax_document')
      ->join('invoice', 'tax_document.invoice_id', '=', 'invoice.id')
      ->join('client', 'invoice.client_id', '=', 'client.id')
      ->where($arr)
      ->select([
                'tax_document.id',
                'tax_document.principal_code',
                'tax_document.auth_code',
                'tax_document.emission_date',
                'tax_document.auth_date',
                'tax_document.amount',
                'client.social_reason',
                'client.identification_number'
        ]);      

      //AudioHelper::Audit($branchOffice->company_id, 'Listar notas de crédito de la sucursal: ' . $branchOffice->name . ', Cliente: ' . $client->firstlast_name_social_reason);

      return DataTables::query($debitNotes)->make(true);
    }

    public function store(Request $request){
        $branch           = BranchOffice::where('id', $request['branch_office_id'])->First();         
        $company          = Company::where('id', $branch->company_id)->First();
        $emission_type    = EntityMasterdata::where('id', $branch->emission_type)->First()->code;
        $environment_type = EntityMasterdata::where('id', $branch->environment_type)->First()->code;          
        //$supplier         = Supplier::where('id', $request['supplier_id'])->First();

        $arr = [
          ['entity_id', 8],
          ['code', '05']
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
        $documentType = "04"; // Nota de Credito
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
            'invoice_id'          => $request['invoice_id'],
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
            'is_processed'        => 0
        ]);
        
        foreach ($request['taxDocumentLine'] as $line) {
          $aux = json_decode($line);                  

          $taxDocumentLine = TaxDocumentLine::create([ 
            'tax_document_id'         => $taxdocument->id,
            'referral_document_type'  => $aux->referral_document_type,
            'referral_document'       => $aux->referral_document,
            'doc_emission_date'       => $aux->doc_emission_date,
            'tax_type_code'           => $aux->tax->id,
            'tax_base_amount'         => $aux->tax_base_amount,
            'tax_percentage'          => $aux->tax_percentage,
            'tax_total_amount'        => $aux->tax_total_amount,
            'reason'                  => $aux->reason
          ]);
        }  

        foreach ($request['debitNotePayment'] as $payment) {
            $aux = json_decode($payment);  

              $invPayment = DebitNotePayment::create([
              'mount'             => $aux->mount,
              'tax_document_id'   => $taxdocument->id,
              'payment_type_id'   => $aux->paymentType->id
            ]);
        }  
     
        return $taxdocument->id;
    }

    public function incrementCorrelative($id){
      $arr = [
        ['entity_id', 8],
        ['code', '05']
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

    public function getDebitNoteXML($id){
      $debitNote = TaxDocument::where('id', $id)->select(['xml_generated', 'auth_code'])->First();
      return $debitNote;
    }

    public function getDebitNote($id) {                
        $debitNote = TaxDocument::with('documentLine', 'branch', 'debitPayment')->select("tax_document.*")->where('tax_document.id', $id)->First();

        $environment = $debitNote->branch->environment_type; 
        $emission    = $debitNote->branch->emission_type;               

        $environment_type = EntityMasterdata::where('id', $environment)->First();        
        $emission_type    = EntityMasterdata::where('id', $emission)->First();
        $document_type    = EntityMasterdata::where('id', $debitNote->document_type_id)->First();
        
        $date       = date_create($debitNote->emission_date);
        $dateFormat = date_format($date, "d/m/Y");

        $arr = [];
        $arr['id']               = $debitNote->id;
        $arr['principal_code']   = $debitNote->principal_code;
        $arr['referral_code']    = $debitNote->referral_code;
        $arr['emission_date']    = $dateFormat;
        $arr['amount']           = $debitNote->amount;
        $arr['concept']          = $debitNote->concept;
        $arr['xml_generated']    = $debitNote->xml_generated;
        $arr['auth_code']        = $debitNote->auth_code;
        $arr['auth_date']        = $debitNote->auth_date;
        $arr['emission_type']    = $emission_type->code;
        $arr['environment_type'] = $environment_type->code;
        $arr['invoice_id']       = $debitNote->invoice_id;
        $arr['document_type_id'] = $document_type->code;


        $invoice = Invoice::with('client', 'invoiceLine')->select("invoice.*")->where('invoice.id', $debitNote->invoice_id)->First(); 
        $date       = date_create($invoice->invoice_date);
        $dateFormat = date_format($date, "d/m/Y");        
        $arr['modifiedDocumentEmissionDate'] = $dateFormat;

        $documentLine = $debitNote->documentLine;        
        $client       = $invoice->client;        
        $branch       = $debitNote->branch; 

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

        $arr['document_detail'] = $documentLine;
        
        $auxPayment = [];
        foreach ($debitNote->debitPayment as $payment) {
            $paymentType = EntityMasterdata::where('id', $payment->payment_type_id)->First();            
            $paymentLine = [];  
            $paymentLine["paymentName"]   = $paymentType->name;
            $paymentLine["paymentMount"]  = $payment->mount;
            $paymentLine["paymentCode"]   = $paymentType->code;                                
            array_push($auxPayment, $paymentLine);
        }

        $arr['payments'] = $auxPayment; 
                                     

        $IVA_0  = 0;
        $IVA_12 = 2;
        $IVA_14 = 3;
        $IVA_NO_OBJETO  = 6;
        $IVA_EXENTO     = 7;

        $SUBTOTAL_0   = 0;
        $SUBTOTAL_12  = 0;
        $SUBTOTAL_14  = 0;
        $SUBTOTAL_NO_OBJETO = 0;
        $SUBTOTAL_EXENTO    = 0;

        $SUBTOTAL = 0;
        $TOTAL_12 = 0;
        $TOTAL_14 = 0;

        $productLines = [];
        $taxesIds = [];
        foreach ($documentLine as $line) {                            
            $country_tax = CountryTax::where('id', $line->tax_type_code)->First();            
            $tax_percentage = EntityMasterdata::where('id', $country_tax->tax_percentage_id)->First()->code;

            $SUBTOTAL += $line->tax_base_amount;

            if($tax_percentage == $IVA_12){
                $SUBTOTAL_12  += $line->tax_base_amount;
                $TOTAL_12     += $line->tax_base_amount * ($country_tax->value / 100);
            }
            else{
                if($tax_percentage == $IVA_0){
                    $SUBTOTAL_0 += $line->tax_base_amount;
                }
                else{ 
                    if($tax_percentage == $IVA_14){
                        $SUBTOTAL_14 += $line->tax_base_amount;
                        $TOTAL_14    += $line->tax_base_amount * ($country_tax->value / 100);
                    }
                    else{ 
                        if($tax_percentage == $IVA_NO_OBJETO){
                            $SUBTOTAL_NO_OBJETO += $line->tax_base_amount;
                        }
                        else{ 
                            if($tax_percentage == $IVA_EXENTO){
                                $SUBTOTAL_EXENTO += $line->tax_base_amount;
                            }
                        }
                    }
                }
            }
            $auxTaxes = [];
            
            
            // $invoiceAcumNoTax = 0;
            // foreach ($aux as $producTax) {          
            //     $auxCountry       = CountryTax::where('id', $line->country_tax_id)->First();  
            //     $auxTax           = EntityMasterdata::where('id', $auxCountry->tax_id)->First();
            //     $auxTaxPercentage = EntityMasterdata::where('id', $auxCountry->tax_percentage_id)->First();          
                
            //     $auxVec = [];
            //     $auxVec['tax_code']              = $auxTax->code;
            //     $auxVec['tax_percentage_code']   = $auxTaxPercentage->code;
            //     $auxVec['tax_percentage_value']  = $auxCountry->value;
            //     $auxVec['tax_sub_total']         = round(($auxCountry->value/100) * $line->unit_price * $line->quantity, 2);
                
            //     $invoiceAcumNoTax += ($line->unit_price * $line->quantity);
            //     array_push($auxTaxes, $auxVec);    
            //     array_push($taxesIds, $line->country_tax_id);
            // }
    
            // $auxInvoice["total_12"]           = $TOTAL_12;
            // $auxInvoice["subtotal_12"]        = $SUBTOTAL_12;
            // $auxInvoice["subtotal_exento"]    = $SUBTOTAL_EXENTO;
            // $auxInvoice["subtotal_no_objeto"] = $SUBTOTAL_NO_OBJETO;
            // $auxInvoice["subtotal_0"]         = $SUBTOTAL_0;
            
            // $auxLine['country_tax_id'] = $line->country_tax_id;
            // $auxLine['product_id']     = $product->id;

            // $auxLine['principal_code'] = $product->principal_code;
            // $auxLine['auxiliary_code'] = $product->auxiliary_code;
            // $auxLine['name']           = $product->name; 
            // $auxLine['description']    = $product->description;   
            // $auxLine['countryTax']     = $line->country_tax_id;             
            // $auxLine['quantity']       = $line->quantity;        
            // $auxLine['unit_price']     = $line->unit_price;        
            // $auxLine['line_sub_total'] = ($line->unit_price * $line->quantity);
            
            // foreach ($invoice->invoiceLine as $invoiceLine) {
            //     if($line->product_id == $invoiceLine->product_id){
            //         $auxLine['discount'] = $invoiceLine->discount;  
            //         $auxLine['totalCostNoTax'] = ($line->unit_price * $line->quantity) - (($line->unit_price * $line->quantity) * ($invoiceLine->discount / 100));
            //         break;
            //     }
            // }
                       
            $auxLine['taxes'] = $auxTaxes;
                          
            // $auxLine['totalCostNoTax'] = ($line->unit_price * $line->quantity) - (($line->unit_price * $line->quantity) * ($line->  discount / 100));     
            
            // $invoiceTotalDiscount += (($line->unit_price * $line->quantity) * ($line->discount / 100));
            array_push($productLines, $auxLine);                
        }            
            
        $auxSumTaxes = [];
        $sumTaxes = array_unique($taxesIds);

        foreach ($sumTaxes as $sumValue) {
            $countryHelp    = CountryTax::where('id', $sumValue)->First();            
            $taxType        = EntityMasterdata::where('id', $countryHelp->tax_id)->First();
            $percentageType = EntityMasterdata::where('id', $countryHelp->tax_percentage_id)->First();              
            $sumLine = [];
            $sumLine['code']            = $taxType->code;
            $sumLine['percentage_code'] = $percentageType->code;             
            $sumLine['taxBase']         = self::taxBase($productLines, $sumValue);            
            $sumLine['totalTax']        = intval($sumLine['taxBase']) * floatval(($countryHelp->value / 100));
            array_push($auxSumTaxes, $sumLine);
        }

        $idDocType = EntityMasterdata::where('id', $client->identification_type_id)->First();
        $auxClient = [];        
        $auxClient['idClass']               = $idDocType->name;
        $auxClient['identificationType']    = $idDocType->code;
        $auxClient['identification_number'] = $client->identification_number;
        $auxClient['identification']        = $client->identification_number;
        $auxClient['social_reason']         = $client->social_reason;
        $auxClient['comercial_name']        = $client->comercial_name;
        $auxClient['phone']                 = $client->phone;
        $auxClient['address']               = $client->address;
        $auxClient['email']                 = $client->email;        

        
        $arr["total_12"]           = $TOTAL_12;
        $arr["subtotal_12"]        = $SUBTOTAL_12;
        $arr["subtotal_exento"]    = $SUBTOTAL_EXENTO;
        $arr["subtotal_no_objeto"] = $SUBTOTAL_NO_OBJETO;
        $arr["subtotal_0"]         = $SUBTOTAL_0;
        $arr["subtotal"]           = $SUBTOTAL;
        $arr["total"]              = $SUBTOTAL + $TOTAL_12 + $TOTAL_14;

        $arr['company'] = $auxCompany;
        $arr['branch']  = $auxBranch;
        $arr['client']  = $client;
        //$arr['document_line'] = $productLines;
        $arr['client']  = $auxClient;
        $arr['invoice'] = $invoice;
        $arr['modifiedDocumentCode'] = substr($invoice->referral_code, 0, 3) . "-" . substr($invoice->referral_code, 3, 3) . "-" . substr($invoice->referral_code, 6);
        // $arr['sumarizedTax'] = $auxSumTaxes;
        return $arr;        
    }

    public function xmlAuthorized(Request $request, $id){
        $creditNote = TaxDocument::where('id', $id)->First();            
        $creditNote->auth_date      = $request['auth_date'];
        $creditNote->xml_generated  = $request['xml']; 
        $creditNote->is_processed   = 1;      
        $creditNote->save();
        return 1;
    }
}
