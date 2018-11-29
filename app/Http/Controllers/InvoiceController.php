<?php

namespace App\Http\Controllers;

use App\{Invoice, BranchOffice, AuditUser, Product, Client, EntityMasterdata, Company, CountryTax, CorrelativeDocument, InvoiceLine, InvoiceTax, InvoicePayment, TaxDocument};
use JWTAuth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\AuditHelper;
use DataTables;
use DateTime;
use DateTimeZone;

class InvoiceController extends Controller
{
  	public function __construct(){
  	   //$this->middleware('jwt.auth');
  	}

    public function indexDt($id){
      $arr = [
          ['invoice.company_id', $id],
          ['status', 'F']
      ];
      $invoicesBranchClient = Invoice::with('invoiceLine', 'client')->select("invoice.*")->where($arr)->orderBy('invoice.principal_code', 'desc');     

      //AudioHelper::Audit($branchOffice->company_id, 'Listar Facturas de la Sucursal: ' . $branchOffice->name . ', Cliente: ' . $client->firstlast_name_social_reason);

      return DataTables::eloquent($invoicesBranchClient)->make(true);
    }

    // Lista las facturas que no tienen asociadas Guias de Remisión
    public function indexProcessed($id){

      $branches = BranchOffice::where('company_id', $id)->get()->pluck('id');
            
      $arr = [
        ['document_type_id', 45],
        ['is_processed', 1],
        ['is_deleted', 0]
      ];

      $taxDocument = TaxDocument::where($arr)->whereIn('tax_document.branch_office_id', $branches)->get()->pluck('invoice_id');

      $arr = [
        ['invoice.company_id', $id],
        ['invoice.status', 'F']
      ];

      $invoice = Invoice::with('invoiceLine', 'client')
      ->where($arr)
      ->whereNotIn('invoice.id', $taxDocument)
      ->orderBy('invoice.principal_code', 'desc')
      ->get();

      return $invoice;
    }

    public function indexProcessed2($id){
      $arr = [
          ['invoice.company_id', $id],
          ['status', 'F']
      ];

      $invoicesBranchClient = Invoice::with('invoiceLine', 'client')->select("invoice.*")->where($arr)->orderBy('invoice.principal_code', 'desc'); 

      //AudioHelper::Audit($branchOffice->company_id, 'Listar Facturas de la Sucursal: ' . $branchOffice->name . ', Cliente: ' . $client->firstlast_name_social_reason);

      return $invoicesBranchClient->get();
    }

    public function preinvoiceDt($id){
      $arr = [
          ['invoice.company_id', $id],
          ['status', 'P'],
          ['is_deleted', 0]
      ];
      $invoicesBranchClient = Invoice::with('invoiceLine', 'client')->select("invoice.*")->where($arr)->orderBy('invoice.invoice_date', 'desc');      

      //AudioHelper::Audit($branchOffice->company_id, 'Listar Facturas de la Sucursal: ' . $branchOffice->name . ', Cliente: ' . $client->firstlast_name_social_reason);

      return DataTables::eloquent($invoicesBranchClient)->make(true);
    }

    public function deleteCorrelative($id){
      $invoice = Invoice::where('id', $id)->First();
      $invoice->principal_code = "000000000";
      $invoice->referral_code  = "000000000000000";      
      $invoice->auth_code      = "0000000000000000000000000000000000000000000000000";
      $invoice->save();
      return 1;
    }

    public function updatePreInvoice($id){
        $invoice = Invoice::where('id', $id)->First();
        $auxCompany = Company::where('id', $invoice->company_id)->First();
        $emission_type    = EntityMasterdata::where('id', $auxCompany->emission_type)->First()->code;
        $environment_type = EntityMasterdata::where('id', $auxCompany->environment_type)->First()->code;

        $arr = [
          ['entity_id', 8],
          ['code', '01']
        ];

        $docTypeInv = EntityMasterdata::where($arr)->First();

        $arr = [
          ['company_id', $invoice->company_id],
          ['document_type_id', $docTypeInv->id]
        ];        
        
        $company  = Company::where('id', $invoice->company_id)->First();
        $branch   = BranchOffice::where('id', $invoice->branch_office_id)->First();        
        $correlative = CorrelativeDocument::where($arr)->First();
        $invNum = $correlative->increment_number;

        $stringAux = "" . $invNum;
        $stringCorrelative = str_pad($stringAux, 9, "0", STR_PAD_LEFT);

        $serie = str_pad($company->emission_code . $branch->emission_point, 6, "0", STR_PAD_LEFT);
        $invNumber = '' . $serie . $stringCorrelative;                

        $date = new DateTime("now", new DateTimeZone('America/Guayaquil') );
        $invoiceDate = $date->format('Y-m-d');        
      
        $day    = $date->format('d');
        $month  = $date->format('m');
        $year   = $date->format('Y');
        $documentType = "01";
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

        $invoice->principal_code = $stringCorrelative;
        $invoice->referral_code  = $invNumber;
        $invoice->invoice_date   = $invoiceDate;
        $invoice->auth_code      = $auth_code;
        $invoice->save();
        return 1;
    }

    public function getInvoicesBranchOffice(Request $Request) {
		  $invoices_branch = Invoice::where('branch_oficce_id', $request['branch'])->get();
		  $branchOffice = BranchOffice::where('id', $request['branch'])->First();
      //AuditHelper::Audit($branchOffice->company_id, 'Listar Facturas de la Sucursal: ' . $branchOffice->name);
      return $invoices_branch;
    }

    public function getInvoiceXML($id){
      $invoice = Invoice::where('id', $id)->First();
      return $invoice;
    }

    public function getInvoice($id) {
      #$invoicesBranchClient = Invoice::data($id)->First();
		  $invoicesBranchClient = Invoice::with('invoiceLine', 'client', 'company', 'branch')->select("invoice.*")->where('invoice.id', $id)->First();		  

      $IVA_0  = 1;
      $IVA_12 = 2;
      $IVA_14 = 3;
      $IVA_NO_OBJETO  = 4;
      $IVA_EXENTO     = 5;

      $SUBTOTAL_0   = 0;
      $SUBTOTAL_12  = 0;
      $SUBTOTAL_14  = 0;
      $SUBTOTAL_NO_OBJETO = 0;
      $SUBTOTAL_EXENTO    = 0;

      $TOTAL_12 = 0;      

      $auxClient   = $invoicesBranchClient->client;      
      $auxBranch   = $invoicesBranchClient->branch;
      $lines       = $invoicesBranchClient->invoiceLine;

      $date       = date_create($invoicesBranchClient->invoice_date);
      $dateFormat = date_format($date,"d/m/Y");      

      $auxInvoice = [];
      $auxInvoice['principal_code']   = $invoicesBranchClient->principal_code;
      $auxInvoice['invoice_date']     = $dateFormat;
      $auxInvoice['auth_code']        = $invoicesBranchClient->auth_code;
      $auxInvoice['auth_date']        = $invoicesBranchClient->auth_date;
      $auxInvoice['emission_type']    = $invoicesBranchClient->emission_type;
      $auxInvoice['environment_type'] = $invoicesBranchClient->environment_type;
      $auxInvoice['total_discount']   = $invoicesBranchClient->total_discount;
      $auxInvoice['total_ice']        = $invoicesBranchClient->total_ice;
      $auxInvoice['total_iva']        = $invoicesBranchClient->total_iva;
      $auxInvoice['total_invoice']    = $invoicesBranchClient->total_invoice;
      $auxInvoice['export_invoice']   = $invoicesBranchClient->export_invoice;

      if($invoicesBranchClient->export_invoice){        
        $auxInvoice['inco_term']              = ucwords($invoicesBranchClient->inco_term);
        $auxInvoice['place_inco_term']        = $invoicesBranchClient->place_inco_term;
        
        if($invoicesBranchClient->source_country != NULL){
          $auxInvoice['source_country'] = self::searchCountryCode($invoicesBranchClient->source_country);
          $auxInvoice['source_country_name'] = self::searchCountryName($invoicesBranchClient->source_country);
        }

        $auxInvoice['source_harvour']         = $invoicesBranchClient->source_harvour;
        $auxInvoice['destination_harvour']    = $invoicesBranchClient->destination_harvour;
        
        if($invoicesBranchClient->destination_country != NULL){
          $auxInvoice['destination_country'] = self::searchCountryCode($invoicesBranchClient->destination_country);
          $auxInvoice['destination_country_name'] = self::searchCountryName($invoicesBranchClient->destination_country);
        }
        
        if($invoicesBranchClient->seller_country != NULL)
          $auxInvoice['seller_country'] = self::searchCountryCode($invoicesBranchClient->seller_country);

        $auxInvoice['inco_term_total_no_tax'] = ucwords($invoicesBranchClient->inco_term_total_no_tax);
        $auxInvoice['international_cargo']    = $invoicesBranchClient->international_cargo;
        $auxInvoice['international_secure']   = $invoicesBranchClient->international_secure;
        $auxInvoice['custom_expenditures']    = $invoicesBranchClient->custom_expenditures;
        $auxInvoice['transport_expenditures'] = $invoicesBranchClient->transport_expenditures;    
      }

      //$auxInvoice['tip']              = $request['tip'],
      //'solidarity_discount' => $rerques['solidarity_discount'],

      if($invoicesBranchClient->tip == NULL)
          $auxInvoice['tip'] = 0;
      else{
          $auxInvoice['tip'] = $invoicesBranchClient->tip;
      }

      $auxCompany = [];      
      $auxCompany['id']             = $invoicesBranchClient->company->id;
      $auxCompany['social_reason']  = $invoicesBranchClient->company->name;
      $auxCompany['comercial_name'] = $invoicesBranchClient->company->comercial_name;
      $auxCompany['ruc']            = $invoicesBranchClient->company->ruc;
      $auxCompany['emission_code']  = $invoicesBranchClient->company->emission_code;
      $auxCompany['address']        = $invoicesBranchClient->company->address;
      $environment = EntityMasterdata::where('id', $invoicesBranchClient->company->environment_type)->First();      
      $auxCompany['environment']    = $environment->code;
      $auxCompany['is_artisan']     = $invoicesBranchClient->company->is_artisan;
      
      if($invoicesBranchClient->company->is_artisan){
        $auxCompany['register_number']   = $invoicesBranchClient->company->register_number;
      }
      
      $auxCompany['is_accounting']  = $invoicesBranchClient->company->is_accounting;
      $auxCompany['special_code']   = $invoicesBranchClient->company->special_code;
      $auxCompany['email']          = $invoicesBranchClient->company->email;
      $auxCompany['logo']           = $invoicesBranchClient->company->logo;
      $auxCompany['certP']          = $invoicesBranchClient->company->digital_certificate_pass;


      $auxBranch = [];
      $auxBranch['name']            = $invoicesBranchClient->branch->name;
      $auxBranch['address']         = $invoicesBranchClient->branch->address;
      $auxBranch['emission_point']  = $invoicesBranchClient->branch->emission_point;
      
      $client = $auxClient;
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

      $productLines = [];
      $auxLine      = [];
      $invoiceAcumNoTax     = 0;
      $invoiceTotalDiscount = 0;
      $product_taxes = [];     

      $invTaxes = InvoiceTax::where('invoice_id', $id)->get();

      foreach($invTaxes as $invTax){
        if($invTax->country_tax_id == $IVA_12){
            $SUBTOTAL_12  = $invTax->subtotal;
            $TOTAL_12     = $invTax->subtotal_tax;
        }
        else 
          if($invTax->country_tax_id == $IVA_0){
            $SUBTOTAL_0 = $invTax->subtotal;
          }
          else 
            if($invTax->country_tax_id == $IVA_14){
              $SUBTOTAL_14 = $invTax->subtotal;
            }
            else 
              if($invTax->country_tax_id == $IVA_NO_OBJETO){
                $SUBTOTAL_NO_OBJETO = $invTax->subtotal;
              }
              else 
                if($invTax->country_tax_id == $IVA_EXENTO){
                  $SUBTOTAL_EXENTO = $invTax->subtotal;
                }  

      }

      foreach ($lines as $line) {        
        $product    = Product::with('producttax')->where('product.id', $line->product_id)->First();
        $aux        = $product->producttax;

        $auxTaxes = [];
        
        foreach ($aux as $producTax) {          
          $auxCountry       = CountryTax::where('id', $line->country_tax_id)->First();  
          $auxTax           = EntityMasterdata::where('id', $auxCountry->tax_id)->First();
          $auxTaxPercentage = EntityMasterdata::where('id', $auxCountry->tax_percentage_id)->First();          
          
          $auxVec = [];
          $auxVec['tax_code']              = $auxTax->code;
          $auxVec['tax_percentage_code']   = $auxTaxPercentage->code;
          $auxVec['tax_percentage_value']  = $auxCountry->value;
          $auxVec['tax_sub_total']         = round(($auxCountry->value/100) * $line->unit_price * $line->quantity, 2);
          
          $invoiceAcumNoTax += ($line->unit_price * $line->quantity);
          array_push($auxTaxes, $auxVec);

        }

        $auxInvoice["total_12"]           = $TOTAL_12;
        $auxInvoice["subtotal_12"]        = $SUBTOTAL_12;
        $auxInvoice["subtotal_exento"]    = $SUBTOTAL_EXENTO;
        $auxInvoice["subtotal_no_objeto"] = $SUBTOTAL_NO_OBJETO;
        $auxInvoice["subtotal_0"]         = $SUBTOTAL_0;
        
        $auxLine['principal_code'] = $product->principal_code;
        $auxLine['auxiliary_code'] = $product->auxiliary_code;
        $auxLine['name']           = $product->name; 
        $auxLine['description']    = $product->description;                
        $auxLine['quantity']       = $line->quantity;        
        $auxLine['unit_price']     = $line->unit_price;        
        $auxLine['line_sub_total'] = ($line->unit_price * $line->quantity) - (($line->unit_price * $line->quantity) * ($line->discount / 100));
        $auxLine['discount']       = $line->discount;
        $auxLine['taxes']          = $auxTaxes;
                      
        $auxLine['totalCostNoTax'] = ($line->unit_price * $line->quantity) - (($line->unit_price * $line->quantity) * ($line->discount / 100));     
        
        $invoiceTotalDiscount += (($line->unit_price * $line->quantity) * ($line->discount / 100));
        array_push($productLines, $auxLine);                
      }

      $auxInvoice['total_discount']     = $invoiceTotalDiscount;
      $auxInvoice['total_without_tax']  = $invoiceAcumNoTax;

      $payments   = InvoicePayment::where('invoice_id', $invoicesBranchClient->id)->get();
      $auxPayment = [];

      foreach ($payments as $payment) {
        $paymentType = EntityMasterdata::where('id', $payment->payment_type_id)->First();
        $timeUnit    = EntityMasterdata::where('id', $payment->unit_time_id)->First();        
        
        $paymentLine = [];  
        $paymentLine["paymentName"]   = $paymentType->name;
        $paymentLine["paymentMount"]  = $payment->mount;
        $paymentLine["paymentCode"]   = $paymentType->code;
        $paymentLine["timeLimit"]     = $payment->time_limit;
        $paymentLine["timeUnit"]      = NULL;
        if($timeUnit)
          $paymentLine["timeUnit"] = $timeUnit->name;

        array_push($auxPayment, $paymentLine);
      } 

      $sumTaxes = InvoiceTax::where('invoice_id', $id)->get();
      $auxSumTaxes = [];

      foreach ($sumTaxes as $sumValue) {
        $countreyHelp   = CountryTax::where('id', $sumValue->country_tax_id)->First();
        $taxType        = EntityMasterdata::where('id', $countreyHelp->tax_id)->First();
        $percentageType = EntityMasterdata::where('id', $countreyHelp->tax_percentage_id)->First();  
        $sumLine = [];
        $sumLine['code']            = $taxType->code;
        $sumLine['percentage_code'] = $percentageType->code;
        $sumLine['taxBase']         = $sumValue->subtotal;
        $sumLine['totalTax']        = $sumValue->subtotal_tax;
        array_push($auxSumTaxes, $sumLine);
      }

      //de esta manera se crea el array que contiene todos los datos que son necesarios para la creación del XML / PDF
      $invoiceElement = [];
      $invoiceElement['invoice']          = $auxInvoice;
      $invoiceElement['invoice_line']     = $productLines;
      $invoiceElement['client']           = $auxClient;
      $invoiceElement['company']          = $auxCompany;
      $invoiceElement['branch']           = $auxBranch;
      $invoiceElement['invoice_payment']  = $auxPayment;
      $invoiceElement['sumarizedTax']     = $auxSumTaxes;      

      //dd($invoiceElement);

      //AudioHelper::Audit($branchOffice->company_id, 'Listar Facturas de la Sucursal: ' . $branchOffice->name . ', Cliente: ' . $client->firstlast_name_social_reason);

      return $invoiceElement;
    }

    public function getInvoiceToCreditNote($id) {
      #$invoicesBranchClient = Invoice::data($id)->First();
      $invoicesBranchClient = Invoice::with('invoiceLine', 'client', 'company', 'branch')->select("invoice.*")->where('invoice.id', $id)->First();      

      $auxClient   = $invoicesBranchClient->client;      
      $auxBranch   = $invoicesBranchClient->branch;
      $lines       = $invoicesBranchClient->invoiceLine;

      $arrAux = [
        ["invoice_id", $id],
        ['is_processed', 1]
      ];
      
      $creditNotes = TaxDocument::with('creditNoteLine')->Where($arrAux)->get();

      $date       = date_create($invoicesBranchClient->invoice_date);
      $dateFormat = date_format($date,"d/m/Y");      

      $auxInvoice = [];
      $auxInvoice['id']               = $id;
      $auxInvoice['principal_code']   = $invoicesBranchClient->principal_code;
      $auxInvoice['invoice_date']     = $dateFormat;

      $productLines = [];
      $auxLine      = [];
      $invoiceAcumNoTax     = 0;
      $invoiceTotalDiscount = 0;
      $product_taxes = [];     

      foreach ($lines as $line) {        
        $product    = Product::with('producttax')->where('product.id', $line->product_id)->First();
        $aux        = $product->producttax;

        $auxTaxes = [];

        foreach ($aux as $producTax) {          
          $auxCountry       = CountryTax::where('id', $line->country_tax_id)->First();  
          $auxTax           = EntityMasterdata::where('id', $auxCountry->tax_id)->First();
          $auxTaxPercentage = EntityMasterdata::where('id', $auxCountry->tax_percentage_id)->First();          
          
          $auxVec = [];
          $auxVec['tax_id']                = $line->country_tax_id;
          $auxVec['tax_code']              = $auxTax->code;
          $auxVec['tax_percentage_code']   = $auxTaxPercentage->code;
          $auxVec['tax_percentage_name']   = $auxTaxPercentage->name;
          $auxVec['tax_percentage_value']  = $auxCountry->value;
          $auxVec['tax_sub_total']         = round(($auxCountry->value/100) * $line->unit_price * $line->quantity, 2);
          
          $invoiceAcumNoTax += ($line->unit_price * $line->quantity);
          array_push($auxTaxes, $auxVec);

        }

        $auxLine['id']             = $line->id;
        $auxLine['check']          = false;
        $auxLine['country_tax_id'] = $line->country_tax_id;
        $auxLine['product_id']     = $product->id;
        $auxLine['principal_code'] = $product->principal_code;
        $auxLine['auxiliary_code'] = $product->auxiliary_code;
        $auxLine['name']           = $product->name; 
        $auxLine['description']    = $product->description;
        $auxLine['laboratory']     = ($product->laboratory==null)?'':$product->laboratory;
        $auxLine['generic']        = $product->generic;
        $auxLine['location']       = $product->location;
        $auxLine['taxes']          = $auxTaxes;

        $date       = date_create($product->expired_date);
        $dateFormat = date_format($date,"d/m/Y"); 

        $auxLine['expired_date']   = $dateFormat;

        $quantity_discount = 0;

        if(isset($creditNotes)) if(count($creditNotes)>0) $quantity_discount = self::getQuantity($creditNotes, $product->id);

        $auxLine['quantity']       = $line->quantity - $quantity_discount;        
        $auxLine['unit_price']     = $line->unit_price;   
        $auxLine['line_sub_total'] = ($line->unit_price * $auxLine['quantity'] ) - (($line->unit_price * $auxLine['quantity'] ) * ($line->discount / 100));     
        //$auxLine['line_sub_total'] = ($line->unit_price * $line->quantity) - (($line->unit_price * $line->quantity) * ($line->discount / 100));
        $auxLine['discount']       = $line->discount;
                      
        $auxLine['totalCostNoTax'] = ($line->unit_price * $auxLine['quantity']) - (($line->unit_price * $auxLine['quantity']) * ($line->discount / 100));
        // $auxLine['totalCostNoTax'] = ($line->unit_price * $line->quantity) - (($line->unit_price * $line->quantity) * ($line->discount / 100));     
        $invoiceTotalDiscount += (($line->unit_price * $auxLine['quantity']) * ($line->discount / 100));
        // $invoiceTotalDiscount += (($line->unit_price * $line->quantity) * ($line->discount / 100));
        array_push($productLines, $auxLine);                
      }

      $auxInvoice['total_discount']     = $invoiceTotalDiscount;
      $auxInvoice['total_without_tax']  = $invoiceAcumNoTax;

      //de esta manera se crea el array que contiene todos los datos que son necesarios para la creación del XML / PDF
      $invoiceElement = [];
      $invoiceElement['invoice']          = $auxInvoice;
      $invoiceElement['invoice_line']     = $productLines;

      //dd($invoiceElement);

      //AudioHelper::Audit($branchOffice->company_id, 'Listar Facturas de la Sucursal: ' . $branchOffice->name . ', Cliente: ' . $client->firstlast_name_social_reason);

      return $invoiceElement;
    }

    public function getQuantity($taxdocument, $product_id){
        $acum = 0;
        foreach ($taxdocument as $value) {
           foreach ($value->creditNoteLine as $detail) {
             if($detail->product_id == $product_id){
                $acum += $detail->quantity;
                break;
             }
           }
        }
        return $acum;
    }

    public function getInvoicesByClient($idcompany, $idclient) {
      $arr = [
          ['invoice.company_id', $idcompany],
          ['invoice.client_id', $idclient],
          ['invoice.status', 'F'],
          ['invoice.is_deleted', 0]
      ];
      $invoicesBranchClient = Invoice::with('invoiceLine', 'client', 'company', 'branch')->select("invoice.*")->where($arr)->get();      

      return $invoicesBranchClient;
    }

    public function searchCountryCode($id){
        return EntityMasterdata::where('id', $id)->First()->code;        
    }

    public function searchCountryName($id){
        return EntityMasterdata::where('id', $id)->First()->name;        
    }

    public function xmlAuthorized(Request $request, $id){
        $currentInvoice = Invoice::where('id', $id)->First();            
        $currentInvoice->auth_date      = $request['auth_date'];
        $currentInvoice->xml_generated  = $request['xml'];
        $currentInvoice->status         = 'F';
        $currentInvoice->save();
        return 1;
    }

    public function getInvoicesBranchOfficeClientProduct(Request $Request) {

    	$arr = [
            ['branch_oficce_id', 	$request['branch']],            
            ['client_id', 			$request['client']],
            ['product_id', 			$request['product']]
        ];

		  $invoicesBranchClient = Invoice::where($arr)->get();

		  $branchOffice 	= BranchOffice::where('id', $request['branch'])->First();
		  $client 		= Cliente::where('id', 		$request['client'])->First();
		  $product 		= Product::where('id', 		$request['product'])->First(); 

      //AuditHelper::Audit($branchOffice->company_id, 'Listar Facturas de la Sucursal: ' . $branchOffice->name . ', Cliente: ' . $client->firstlast_name_social_reason . ', Producto: ' . $product->name);

      return $invoicesBranchClient;
    }

    public function incrementCorrelative($id){
      $arr = [
        ['entity_id', 8],
        ['code', '01']
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

    /*
      "principal_code"    : entity.principal_code,
      "invoicedate"       : entity.invoicedate ,
      "concept"           : entity.concept, // Por definir que dato colocar aquí
      "referral_code"     : entity.referral_code, //Por Definir que dato colocar aquí
      "total_discount"    : entity.total_discount, 
      "total_ice"         : entity.total_ice,
      "total_iva"         : entity.total_iva,
      "total_invoice"     : entity.total_invoice,
      "emission_type"     : entity.emission_type, // Debe asocisarse a un valor en BD
      "environment_type"  : entity.environment_type, // Debe asocisarse a un valor en BD
      "status"            : entity.status,
      "branch_office_id"  : entity.branch_office_id, // Debe asocisarse a un valor en BD
      "company_id"        : entity.company_id, // Debe asociarse a un valor en BD
      "client_id"         : entity.client_id,
      "invoicePayment[]"  : entity.invoicepayment,
      "invoiceTax[]"      : entity.invoicetax,
      "invoiceLine[]"     : entity.invoiceline
    */
    public function store(Request $request) {    

        $auxCompany = Company::where('id', $request['company_id'])->First();
        $emission_type    = EntityMasterdata::where('id', $auxCompany->emission_type)->First()->code;
        $environment_type = EntityMasterdata::where('id', $auxCompany->environment_type)->First()->code;

        $arr = [
          ['entity_id', 8],
          ['code', '01']
        ];

        $docTypeInv = EntityMasterdata::where($arr)->First();

        $arr = [
          ['company_id', $request['company_id']],
          ['document_type_id', $docTypeInv->id]
        ];        
        
        $company  = Company::where('id', $request['company_id'])->First();
        $branch   = BranchOffice::where('id', $request['branch_office_id'])->First();        

        if($request['status'] == "P"){
          $correlative = null;
          $invNum = null;
        }
        else{
          $correlative = CorrelativeDocument::where($arr)->First();
          $invNum = $correlative->increment_number;
        }

        $stringAux = "" . $invNum;
        $stringCorrelative = str_pad($stringAux, 9, "0", STR_PAD_LEFT);

        $serie = str_pad($company->emission_code . $branch->emission_point, 6, "0", STR_PAD_LEFT);
        $invNumber = '' . $serie . $stringCorrelative;                

        $date = new DateTime("now", new DateTimeZone('America/Guayaquil') );
        $invoiceDate = $date->format('Y-m-d');        
        // $day    = date('d');
        // $month  = date('m');
        // $year   = date('Y');
        $day    = $date->format('d');
        $month  = $date->format('m');
        $year   = $date->format('Y');
        $documentType = "01";
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

        if($request['status'] == 'P'){
          $stringCorrelative = '000000000';
          $invNumber   = '000000000000000';          
          $auth_code   = '0000000000000000000000000000000000000000000000000';
        }

        $invoice = Invoice::create([
        	  'principal_code'	 	  => $stringCorrelative,
            'referral_code'       => $invNumber,
            'invoice_date'        => $invoiceDate,        	  
            'concept'			 	      => $request['concept'],        	  
            'total_discount'      => $request['total_discount'],
            'total_ice'           => $request['total_ice'],
            'total_iva'           => $request['total_iva'],
            'total_invoice'       => $request['total_invoice'],
            'tip'                 => $request['tip'],
            'solidarity_discount' => $request['solidarity_discount'],
            'auth_code'           => $auth_code,
            'emission_type'       => $emission_type,
            'environment_type'    => $environment_type,
            'status'              => $request['status'],
            'client_id'           => $request['client_id'],
            'branch_office_id'    => $request['branch_office_id'],            
            'company_id'          => $request['company_id'],
            // Datos de Exportación
            'export_invoice'      => $request['export_invoice'],
            'inco_term'           => $request['inco_term'],
            'place_inco_term'     => $request['place_inco_term'],
            'source_country'      => $request['source_country'],
            'source_harvour'      => $request['source_harvour'],
            'seller_country'      => $request['seller_country'],
            'destination_country' => $request['destination_country'],
            'destination_harvour' => $request['destination_harvour'],
            'inco_term_total_no_tax' => $request['inco_term_total_no_tax'],
            'international_cargo' => $request['international_cargo'],
            'international_secure'=> $request['international_secure'],
            'custom_expenditures' => $request['custom_expenditures'],
            'transport_expenditures' => $request['transport_expenditures']
        ]);
        
        foreach ($request['invoiceLine'] as $line) {
          $aux = json_decode($line);                  

          $invoiceLine = InvoiceLine::create([ 
            'quantity'    => $aux->quantity,
            'unit_price'  => $aux->unit_price,
            'discount'    => $aux->discount,
            'invoice_id'  => $invoice->id,
            'product_id'  => $aux->product_id,
            'country_tax_id' => $aux->country_tax_id
          ]);
        }    

        foreach ($request['invoiceTax'] as $taxes) {
          $aux = json_decode($taxes); 
          $invTaxes = InvoiceTax::create([ 
            'invoice_id'      => $invoice->id,
            'country_tax_id'  => $aux->country_tax_id,
            'subtotal'        => $aux->subtotal,
            'subtotal_tax'    => $aux->subtotal_tax            
          ]);
        }                 
        
        if(isset($request['invoicePayment'])){
          if(count($request['invoicePayment']) > 0)
          foreach ($request['invoicePayment'] as $payment) {
            $aux      = json_decode($payment);            
            $bank = NULL;
            $document_number = NULL;
            
            if(is_object($aux->bank)){
              $bank = $aux->bank->id;
            }

            if(isset($aux->document_number)){
              $document_number = $aux->document_number;
            }

            $invPayment = InvoicePayment::create([
              'document_number'   => $document_number,
              'mount'             => $aux->mount,
              'invoice_id'        => $invoice->id,
              'payment_type_id'   => $aux->paymentType->id,
              'bank_id'           => $bank
            ]);
          }
        }
      
        return $invoice->id;
    }

    public function changeStatus(Request $request, $id){
      $invoice = Invoice::where('id', $id)->First();
      if($invoice->status == 0){
        $invoice->status = 1;
        $invoice->save();

        foreach ($request['payments'] as $payment) {
          $invPayment = InvoicePayment::create([
            'document_number'   => $payment->document_number,
            'mount'             => $payment->mount,
            'invoice_id'        => $invoice->id,
            'payment_type'      => $payment->payment_type,
            'bank_id'           => $payment->bank_id
          ]);
        }
      }
      else{
        return 0;
      }

      return 1;
    }

    public function edit($id){      
      $invoice = Invoice::with('invoiceLine', 'invoicePayment', 'client', 'source_countries', 'destination_countries', 'seller_countries')->select("invoice.*")->where('invoice.id', $id)->First();
      $invoiceLine = $invoice->invoiceLine->pluck('product_id');
      
      $invoiceObject = [];
      $invoiceObject["id"]                = $invoice->id;
      $invoiceObject["principal_code"]    = $invoice->principal_code;
      $invoiceObject["invoice_date"]      = $invoice->invoice_date;
      $invoiceObject["concept"]           = $invoice->concept;
      $invoiceObject["referral_code"]     = $invoice->referral_code;
      $invoiceObject["total_discount"]    = $invoice->total_discount;
      $invoiceObject["total_ice"]         = $invoice->total_ice;
      $invoiceObject["total_invoice"]     = $invoice->total_invoice;
      $invoiceObject["tip"]               = $invoice->tip;
      $invoiceObject["xml_generated"]     = $invoice->xml_generated;
      $invoiceObject["auth_code"]         = $invoice->auth_code;
      $invoiceObject["auth_date"]         = $invoice->auth_date;
      $invoiceObject["emission_type"]     = $invoice->emission_type;
      $invoiceObject["environment_type"]  = $invoice->environment_type;
      $invoiceObject["status"]            = $invoice->status;
      $invoiceObject["branch_office_id"]  = $invoice->branch_office_id;
      $invoiceObject["company_id"]        = $invoice->company_id;
      $invoiceObject["client_id"]         = $invoice->client_id;
      $invoiceObject["is_deleted"]        = $invoice->is_deleted;
      $invoiceObject["invoice_line"]      = [];
      $invoiceObject["invoice_payment"]   = [];
      $invoiceObject["client"]            = $invoice->client;
      // Datos de Exportación
      $invoiceObject['export_invoice']      = $invoice->export_invoice;
      $invoiceObject['inco_term']          = $invoice->inco_term;
      $invoiceObject['place_inco_term']     = $invoice->place_inco_term;
      $invoiceObject['source_country']      = $invoice->source_country;
      $invoiceObject['source_harvour']      = $invoice->source_harvour;
      $invoiceObject['seller_country']      = $invoice->seller_country;
      $invoiceObject['destination_country'] = $invoice->destination_country;
      $invoiceObject['destination_harvour'] = $invoice->destination_harvour;
      $invoiceObject['inco_term_total_no_tax'] = $invoice->inco_term_total_no_tax;
      $invoiceObject['international_cargo']    = $invoice->international_cargo;
      $invoiceObject['international_secure']   = $invoice->international_secure;
      $invoiceObject['custom_expenditures']    = $invoice->custom_expenditures;
      $invoiceObject['transport_expenditures'] = $invoice->transport_expenditures;

      $invoiceObject['source_countries']      = $invoice->source_countries;
      $invoiceObject['destination_countries'] = $invoice->destination_countries;
      $invoiceObject['seller_countries']      = $invoice->seller_countries;

      $elements = [];
      $aux = [];
      foreach ($invoice->invoiceLine as $line) {
        $product = Product::where('id', $line->product_id)->First();
        $tax = CountryTax::where('id', $line->country_tax_id)->First();
        $aux["id"]             = $line->id;
        $aux["quantity"]       = $line->quantity;
        $aux["unit_price"]     = $line->unit_price;
        $aux["discount"]       = $line->discount;        
        $aux["country_tax_id"] = $line->country_tax_id;
        $aux["product"]        = $product;
        $aux["tax"]            = $tax;
        array_push($elements, $aux);  
      }

      $invoiceObject["invoice_line"] = $elements;

      $elements = [];
      $aux = [];
      foreach ($invoice->invoicePayment as $payment) {
        $paymentType = EntityMasterdata::where('id', $payment->payment_type_id)->First();
        $aux["id"]              = $payment->id;
        $aux["document_number"] = $payment->document_number;
        $aux["mount"]           = $payment->mount;
        $aux["time_limit"]      = $payment->time_limit;        
        $aux["unit_time_id"]    = $payment->unit_time_id;
        $aux["invoice_id"]      = $payment->invoice_id;
        $aux["paymentType"]     = $paymentType;
        $aux["bank_id"]         = $payment->bank_id;
        array_push($elements, $aux);  
      }

      $invoiceObject["invoice_payment"] = $elements;
      return $invoiceObject;
    }

    public function update(Request $request, $id){        

        $invoice = Invoice::where('id', $id)->First();                  
        $invoice->total_discount = $request['total_discount'];
        $invoice->total_ice      = $request['total_ice'];
        $invoice->total_iva      = $request['total_iva'];
        $invoice->total_invoice  = $request['total_invoice'];

        // Datos de Exportación
        $invoice->export_invoice  = $request['export_invoice'];
        $invoice->inco_term           = $request['inco_term'];
        $invoice->place_inco_term     = $request['place_inco_term'];
        $invoice->source_country      = $request['source_country'];
        $invoice->source_harvour      = $request['source_harvour'];
        $invoice->seller_country      = $request['seller_country'];
        $invoice->destination_country = $request['destination_country'];
        $invoice->destination_harvour = $request['destination_harvour'];
        $invoice->inco_term_total_no_tax = $request['inco_term_total_no_tax'];
        $invoice->international_cargo = $request['international_cargo'];
        $invoice->international_secure= $request['international_secure'];
        $invoice->custom_expenditures = $request['custom_expenditures'];
        $invoice->transport_expenditures = $request['transport_expenditures'];

        $invoice->save();   
        
        $invoiceL = InvoiceLine::where('invoice_id', $id)->get();
        $invoiceT = InvoiceTax::where('invoice_id', $id)->get();
        $invoiceP = InvoicePayment::where('invoice_id', $id)->get();
        
        foreach ($invoiceL as $aux) { $aux->delete(); }
        foreach ($invoiceT as $aux) { $aux->delete(); }
        foreach ($invoiceP as $aux) { $aux->delete(); }
        
        $idAux = "";
        foreach ($request['invoiceLine[]'] as $line) {
          $aux = $line;                                

          $idAux = (isset($aux['product']['id'])) ? $aux['product']['id'] : $aux['product']['product_id'];          

          $invoiceLine = InvoiceLine::create([ 
            'quantity'    => $aux['quantity'],
            'unit_price'  => $aux['unit_price'],
            'discount'    => $aux['discount'],
            'invoice_id'  => $invoice->id,
            'product_id'  => $idAux,                                
            'country_tax_id' => $aux['country_tax_id']
          ]);
        }            

        foreach ($request['invoiceTax[]'] as $taxes) {
          $aux = $taxes; 
          $invTaxes = InvoiceTax::create([ 
            'invoice_id'      => $invoice->id,
            'country_tax_id'  => $aux['country_tax_id'],
            'subtotal'        => $aux['subtotal'],
            'subtotal_tax'    => $aux['subtotal_tax']            
          ]);
        }                 
                
        foreach ($request['invoicePayment[]'] as $payment) {
          $aux = $payment; 
          $bank = NULL;

          if(array_key_exists('bank', $aux)){
            $bank = $aux['bank']['id'];
          }           

          $invPayment = InvoicePayment::create([
            'document_number'   => $aux['document_number'],
            'mount'             => $aux['mount'],
            'invoice_id'        => $invoice->id,
            'payment_type_id'   => $aux['paymentType']['id'],
            'bank_id'           => $bank
          ]);
        }

      return 1;
    }

    public function deleteInvoice($id){
      $invoice = Invoice::where('id', $id)->First();

      if(isset($invoice)){
        if($invoice->status == 'P'){
          
          $invoice->is_deleted = 1;
          $invoice->save();
          // $lines    = InvoiceLine::where('invoice_id', $id)->get();
          // $payments = InvoicePayment::where('invoice_id', $id)->get();
          
          // foreach ($lines as $line) {
          //   $line->is_deleted = 1;
          //   $line->save();
          // }

          // foreach ($payments as $payment) {
          //   $payment->is_deleted = 1;
          //   $payment->save();
          // }

          return 1;
        }
        else
          return 2;
      }
      return 0;
    }



    // public function test(){        
    //     $stringAux = "000000070";
    //     $stringCorrelative = str_pad($stringAux, 9, "0", STR_PAD_LEFT);

    //     $serie = str_pad("001001", 6, "0", STR_PAD_LEFT);
    //     $invNumber = '' . $serie . $stringCorrelative;

    //     $invoiceDate = date('Y-m-d');
    //     $day    = "03";
    //     $month  = "04";
    //     $year   = "2018";
    //     $documentType = "01";
    //     $ruc = str_pad("1714147491001", 13, "0", STR_PAD_LEFT);

    //     $invCount = 222;
    //     $numericField = str_pad($invCount, 8, "0", STR_PAD_LEFT);
    //     $auth_code = "" . $day . $month . $year . $documentType . $ruc . 1 . $invNumber . $numericField . 1;

    //     $codeArr = str_split($auth_code);
    //     $module11 = 7;
    //     $numArr = [];
    //     for ($i=0; $i < 48; $i++) { 
          
    //       if($module11 == 1) 
    //         $module11 = 7;
          
    //       $numArr[$i] = $module11;
    //       $module11--;
    //     }

    //     $acum = 0;
    //     $auxNumber = 0;
    //     for ($i=0; $i < 48; $i++) { 
    //       $auxNumber = (int)$codeArr[$i] * $numArr[$i];
    //       $acum += $auxNumber;
    //       #echo "Elemento: " . (int)$codeArr[$i] . "x" .  $numArr[$i] . " = " . ((int)$codeArr[$i] * $numArr[$i]) . "<br>";
    //       #echo "Valor acumulador: " . $acum . "<br><br>";          
    //     }

    //     #echo "final: <br>";
    //     #dd($acum);
    //     $module   = $acum % 11;        
    //     $verDigit = 11 - $module; 
        
    //     if($verDigit > 9){
    //       //cuando el dígito verificador sea un número con dos cifras, se tomará sólo la primera
    //       //caso de ejemplo 020420180117141474910011001001000000066000002221
    //       //la cifra acumulada tiene un valor de 364
    //       //364%11 = 1; 
    //       //11 - 1 = 10; el dígito verificador es un número con 2 cifras
    //       $auxDigit = "" . $verDigit;
    //       $verDigit = substr($auxDigit, 0, 1);          
    //     }

    //     $auth_code = $auth_code . $verDigit;

    //     return $auth_code;
    // }
}
