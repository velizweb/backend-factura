<?php

namespace App\Helpers;
use App\RoleModulePermission;
use App\User;

class PermissionHelper {
 	public static function userHasAccess($user, $module){
        $currentUser = User::where('id', $user)->First();
        
 	}
}