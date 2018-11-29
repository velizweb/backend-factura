<?php

Route::group(['prefix' => 'api'], function()
{

    Route::resource('authenticate',                 'AuthenticateController', ['only' => ['index']]);
    Route::post('authenticate',                     'AuthenticateController@authenticate');
    Route::get('authenticate/user',                 'AuthenticateController@getAuthenticatedUser');
    // MailConfiguration API's
    Route::resource('mailconfiguration',            'MailConfigurationController');
    Route::get('mailconfigurationDtAll',            'MailConfigurationController@indexDtAll');
    Route::get('mailconfigurationDt/{id}',          'MailConfigurationController@indexDt');
    Route::get('mailconfiguration/company/{id}',    'HelperController@getCompanyInfo');
    // AuditUserControler API's
    Route::post('auditActivity',                    'AuditUserController@getActivityUser');
    // UserController API's
    Route::resource('user',                         'UserController', ['except' => ['index']]);
    Route::get('userDt/{id}',                       'UserController@index'); 
    Route::get('userDtAll',                         'UserController@indexAll'); 
    // ClientController API's
    Route::resource('client',                       'ClientController');
    Route::get('clientDt/{id}',                     'ClientController@indexDt');
    Route::post('client/search',                    'ClientController@search');
    Route::get('clientCompany/{IdCompany}',         'ClientController@clientByCompany');
    // SupplierController API's
    Route::resource('supplier',                     'SupplierController');
    Route::get('supplierDt/{id}',                   'SupplierController@indexDt');
    Route::get('supplierFilter/{id}',               'SupplierController@indexFilter');
    Route::post('supplier/search',                  'SupplierController@search');
    // DispatcherController API's
    Route::resource('dispatcher',                   'DispatcherController');
    Route::get('dispatcherDt/{id}',                 'DispatcherController@indexDt');
    Route::get('dispatcherFilter/{id}',             'DispatcherController@indexFilter');
    Route::post('dispatcher/search',                'DispatcherController@search');
    // CompanyController API's
    Route::resource('company',                      'CompanyController');
    Route::get('companyDt/{id}',                    'CompanyController@indexDt'); 
    Route::get('companyDtAll',                      'CompanyController@indexDtAll');  
    Route::put('companyCertificate/{id}',           'CompanyController@updateCertificate');
    Route::get('companyCertificate/{id}',           'CompanyController@getCertificate');
    Route::put('companyLogo/{id}',                  'CompanyController@updateLogo');
    Route::get('companyLogo/{id}',                  'CompanyController@getLogo');
    Route::get('companyDt',                         'CompanyController@indexDt');
    // Permission API's
    Route::resource('permission',                   'PermissionController');
    Route::get('permissionDt',                      'PermissionController@indexDt');  
    Route::post('checkAccess',                      'PermissionController@check');
    // Correlativo de Documentos por Compañia
    Route::resource('correlativedocument',          'CorrelativeDocumentController');  
    Route::get('correlativedocumentbycompany/{id}', 'CorrelativeDocumentController@indexByCompany');  
    // Company TaxYear
    Route::resource('companytaxyear',               'CompanyTaxYearController');
    Route::get('companytaxyearDt/{id}',             'CompanyTaxYearController@indexDt');
    // BranchOfficeController API's
    Route::resource('branch',                       'BranchOfficeController', ['except' => ['index', 'store']]);
    Route::get('branchDt/{id}',                     'BranchOfficeController@indexDt');
    Route::get('branchDtAll',                       'BranchOfficeController@indexDtAll');
    Route::post('branch/{id}',                      'BranchOfficeController@store');
    Route::get('branch/all/{id}',                   'BranchOfficeController@index');
    Route::get('branch/byCompany/{id}',             'BranchOfficeController@byCompany');
    // ProductController API's
    Route::resource('product',                      'ProductController');
    Route::get('productDt/{id}',                    'ProductController@indexDt');   
    Route::get('productFilter/{id}',                'ProductController@indexFilter');  
    Route::post('productimport',                    'ProductController@import');    
    // Product Tax
    Route::resource('producttax',                   'ProductTaxController');
    Route::get('producttaxDefault/{id}',            'ProductTaxController@default');
    // CountryTax
    Route::resource('countryTax',                             'CountryTaxController');
    Route::get('countryTaxDt/{id}',                           'CountryTaxController@indexDt');
    Route::get('countryTaxLista/{id}/{idTax}',                'CountryTaxController@indexLista');
    Route::get('countryTaxDebitNote/{id}/{idTax}/{idTaxPerc}','CountryTaxController@indexDebitNote');
    Route::get('countryTaxListaRetention/{id}/{idretention}', 'CountryTaxController@indexTaxRetention');
    Route::get('countryTaxListaPercentage/{id}/{idTax}',      'CountryTaxController@indexTaxPercentage');
    Route::put('countryTax/update/{id}',                      'CountryTaxController@update');
    // Entity
    Route::resource('entity',                           'EntityController');
    // Entity Masterdata 
    Route::get('entitymasterdataDt',                    'EntityMasterdataController@indexDt');
    Route::get('entitymasterdataEntity/{id}',           'EntityMasterdataController@entitymasterdataByEntity');
    Route::get('entitymasterdataEntityDt/{id}',         'EntityMasterdataController@entitymasterdataByEntityDt');
    Route::resource('entitymasterdata',                 'EntityMasterdataController');
    // PlanController API
    Route::resource('plan',                             'PlanController');
    Route::get('planDt',                                'PlanController@indexDt');
   // FamiliesController
    Route::resource('families',                             'FamiliesController');
    Route::get('familiesDt',                                'FamiliesController@indexDt');
     // AlmacenController
    Route::resource('warehouse',                             'WarehouseController');
    Route::get('warehouseDt',                                'WarehouseController@indexDt');
    Route::get('warehouse/products/{warehouse}/{company}/{branch}',                                'WarehouseController@products');
    Route::post('warehouse/storeEntryProduct',               'WarehouseController@storeEntryProduct');
    Route::post('warehouse/storeEntryTransfer',              'WarehouseController@storeEntryTransfer');
    Route::post('warehouse/storeEntryOutput',                'WarehouseController@storeEntryOutput');
    Route::get('warehouse/getTransfer/{document_number}',    'WarehouseController@getTransfer');
    Route::get('warehouse/acceptTransfer/{document_number}',                   'WarehouseController@acceptTransfer');
    Route::get('warehouse/product/{company}/{warehouse}/{product}/{branch}',              'WarehouseController@getWarehouseProduct');
    Route::get('warehouseMov/{type}',  'WarehouseController@indexMov');

    // Company Plan API's
    Route::resource('companyplan',                      'CompanyPlanController');
    Route::get('companyplanDt',                         'CompanyPlanController@indexDt');
    // InvoiceControler API's
    Route::get('invoiceDt/{id}',                        'InvoiceController@indexDt');
    Route::get('invoice/processed/{id}',                'InvoiceController@indexProcessed');
    Route::resource('invoice',                          'InvoiceController', ['except' => ['edit', 'show']]);
    Route::get('invoice/{id}',                          'InvoiceController@getInvoice'); 
    Route::get('invoice/creditnote/{id}',               'InvoiceController@getInvoiceToCreditNote');        
    Route::post('invoice/xmlAuthorized/{id}',           'InvoiceController@xmlAuthorized');
    Route::get('invoice/getXML/{id}',                   'InvoiceController@getInvoiceXML');
    Route::get('invoice/client/{idcompany}/{idclient}', 'InvoiceController@getInvoicesByClient');
    Route::post('preinvoice',                           'InvoiceController@preInvoice');
    Route::get('preinvoiceDt/{id}',                     'InvoiceController@preinvoiceDt');
    Route::get('preinvoice/edit/{id}',                  'InvoiceController@edit');
    Route::delete('preinvoice/delete/{id}',             'InvoiceController@deleteInvoice');
    Route::get('preinvoice/increment/{id}',             'InvoiceController@incrementCorrelative');
    Route::get('preinvoice/addCorrelative/{id}',        'InvoiceController@updatePreInvoice');
    Route::get('preinvoice/deleteCorrelative/{id}',     'InvoiceController@deleteCorrelative');

    // InvoiceTaxController API's
    Route::resource('invoicetax',                     'InvoiceTaxController');
    Route::get('invoicetaxDt/{id}',                   'InvoiceTaxController@indexDt');
    /* TaxDocument API's*/
    Route::resource('taxdocument',                    'TaxDocumentController');
    Route::get('taxdocumentDt/{id}',                  'TaxDocumentController@indexDt');
    // TaxRetentionController API's
    Route::get('taxdocument/retentionDt/{id}',              'TaxRetentionController@indexDt');
    Route::resource('taxdocument/retention',                'TaxRetentionController', ['except' => ['edit', 'show', 'store']]);
    Route::post('taxdocument/post/retention',               'TaxDocumentController@store');
    Route::get('taxdocument/retention/{id}',                'TaxRetentionController@getRetention');      
    Route::post('taxdocument/retention/xmlAuthorized/{id}', 'TaxRetentionController@xmlAuthorized');
    Route::get('taxdocument/retention/getXML/{id}',         'TaxRetentionController@getRetentionXML');      
    Route::get('taxdocument/increment/{id}',                'TaxRetentionController@incrementCorrelative');

    /* CreditNoteController API's */
    Route::get('creditnoteDt/{id}',                    'CreditNoteController@indexDt');    
    Route::resource('creditnote',                      'CreditNoteController', ['except' => ['edit', 'show', 'store']]);
    Route::post('creditnote/post',                     'CreditNoteController@store');
    Route::get('creditnote/{id}',                      'CreditNoteController@getCreditNote');      
    Route::post('creditnote/xmlAuthorized/{id}',       'CreditNoteController@xmlAuthorized');
    Route::get('creditnote/getXML/{id}',               'CreditNoteController@getCreditNoteXML');      
    Route::get('creditnote/increment/{id}',            'CreditNoteController@incrementCorrelative');

    /* DebitNoteController API's */
    Route::get('debitnoteDt/{id}',                     'DebitNoteController@indexDt');    
    Route::resource('debitnote',                       'DebitNoteController', ['except' => ['edit', 'show', 'store']]);
    Route::post('debitnote/post',                      'DebitNoteController@store');
    Route::get('debitnote/{id}',                       'DebitNoteController@getDebitNote');      
    Route::post('debitnote/xmlAuthorized/{id}',        'DebitNoteController@xmlAuthorized');
    Route::get('debitnote/getXML/{id}',                'DebitNoteController@getDebitNoteXML');      
    Route::get('debitnote/increment/{id}',             'DebitNoteController@incrementCorrelative');

    /* RemissionGuideCntroller API's */
    Route::get('remissionDt/{id}',                     'RemissionController@indexDt');    
    Route::resource('remission',                       'RemissionController', ['except' => ['edit', 'show', 'store']]);
    Route::post('remission/post',                      'RemissionController@store');
    Route::get('remission/{id}',                       'RemissionController@getRemission');      
    Route::post('remission/xmlAuthorized/{id}',        'RemissionController@xmlAuthorized');
    Route::get('remission/getXML/{id}',                'RemissionController@getRemissionXML');      
    Route::get('remission/increment/{id}',             'RemissionController@incrementCorrelative');

    // Audit API's
    Route::get('auditDt', 'AuditUserController@indexDt');
});

Route::get('test', function(){      
    return "Api funcionando correctamente";
});

//Route::get('module', 'InvoiceController@test');