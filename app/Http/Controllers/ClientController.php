<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Client,AuditUser, ClientIdentificationType};
use DataTables; 
use App\Helpers\AuditHelper;
use JWTAuth;

class ClientController extends Controller
{
    public function __construct(){
       #$this->middleware('jwt.auth');
    }

    public function index(){
        $clients = Client::All();
        return $clients;
    }
    
    public function indexDt($id) {
        $arr = [
            ['client.company_id', $id],
            ['client.is_deleted', '<>', 1]            
        ];
        //$clients = Client::with('company')->select("client.*")->where('company_id',$id);
        $clients = Client::with('company', 'identification_type')->select("client.*")->where($arr);
        //AuditHelper::Audit($client->company_id, 'listar clientes');        
        return DataTables::eloquent($clients)->make(true);
    }

    public function show($id) {        
        $client = Client::where($id)->First();
        //AuditHelper::Audit($request['company'], 'ver cliente: ' . $client->firstlast_name_social_reason);
        return $client;
    }

    public function search(Request $request){
        $client = Client::where('social_reason', $request['data'])
                        ->orWhere('comercial_name', $request['data'])
                        ->First();

        return $client;
    }

    public function edit($id) {        
        $client = Client::with('company', 'identification_type')->select("client.*")->where('id', $id)->first();
        //AuditHelper::Audit($request['company'], 'ver cliente: ' . $client->firstlast_name_social_reason);
        return $client;
    }

    public function import(Request $request){
        foreach ($request["clients"] as $client) {
            $client = json_decode($client);
            Client::create([
                'identification_type_id'=> $client->identification_type_id,
                'identification_number' => $client->identification_number,
                'social_reason' => $client->social_reason,
                'comercial_name' => $client->comercial_name,
                'phone' => $client->phone,
                'address' => $client->address,
                'email' => $client->email,
                'company_id' => $client->company_id,
                'is_active' => $client->is_active,
                'is_deleted' => $client->is_deleted 
            ]);
        }
    }

    public function store(Request $request) { 

        $client = Client::create($request->all());
        
        return 1;
    }

    public function update(Request $request, $id) {        

        $client = Client::findOrfail($id)->update($request->all());
        
        //AuditHelper::Audit($client->company_id, 'editar cliente: ' . $client->firstlast_name_social_reason); 

        return 1;                            
    }

    public function destroy($id){
        $client = Client::where('id', $id)->First();

        $client->is_active = 0;
        $client->is_deleted = 1;
        $client->update();

        return 1;

    }

    public function clientByCompany($IdCompany){

        $arr = [
            ['client.company_id', $IdCompany],
            ['client.is_deleted', '<>', 1]            
        ];

        $clients = Client::where($arr)->orderBy('client.social_reason', 'asc')->get();

        return $clients;
    }

    // public function destroy($id) {         
    //     $client = Client::where('id', $id)->First();
    //     $ids = ClientIdentificationType::where('client_id', $client->id)->get();        
    //     foreach ($ids as $id) { $id->delete(); }
    //     //AuditHelper::Audit($client->company_id, 'Eliminar cliente: ' . $client->firstlast_name_social_reason);
    //     $client->delete();               
    //     return 1;
    // }
}
