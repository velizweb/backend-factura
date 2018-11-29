<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MailConfiguration;
use App\Company;
use JWTAuth;

class HelperController extends Controller
{	
	public function __construct(){
        $this->middleware('jwt.auth');
    }

    //este método debe ser pasado al MailconfigurationController una vez esté activado el JWT
    public function getCompanyInfo($id){
        $info = [];
        $company = Company::where('id', $id)->First();

        $arr = [
            ['company_id', $id],
            ['is_active', 1]
        ];
        $mailConfiguration = MailConfiguration::where($arr)->First();        

        $info['companyEmail']   = $company->email;
        $info['companyName']    = $company->name;
        $info['subject']        = is_object($mailConfiguration) ? $mailConfiguration->subject : NULL;
        $info['body']           = is_object($mailConfiguration) ? $mailConfiguration->body : NULL;
        $info['legend']         = is_object($mailConfiguration) ? $mailConfiguration->legend : NULL;

        return $info;
    }
}
