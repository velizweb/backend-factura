<?php

namespace App\Helpers;

use App\AuditUser;
use JWTAuth;

class AuditHelper {
 	public static function Audit($company_id, $concept)
    {
        AuditUser::create([
            'company_id' 	=> $company_id,
            'users_id' 		=> JWTAuth::parseToken()->authenticate()->id,
            'created_at' 	=> date('Y-m-d H:i:s'),
            'concept' 		=> $concept
        ]);
	 }
}