<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;
use Cookie;
use App\{Company, EntityMasterdata, MailConfiguration, UserRole};

class AuthenticateController extends Controller
{

    public function __construct()
   {
       $this->middleware('jwt.auth', ['except' => ['authenticate']]);
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "Auth index";
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');            

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user               = Auth::user();
        $branch             = Auth::user()->branch;
        $userName           = $user->first_name . " " . $user->last_name;        
        $userId             = $user->id;
        $company            = $user->company_id;
        $branch_office      = $branch->id;
        $branch_name        = $branch->name;
        
        $arr = [
            ['entity_id', 11],
            ['id', 28]
        ];
        $countryMasterdata  = EntityMasterdata::where($arr)->First();
        $country            = $countryMasterdata->id;

        $role = UserRole::where('users_id', $user->id)->First();         
        $roleCode = EntityMasterdata::where('id', $role->role_id)->First();            

        $userRole = $roleCode->code;

        if($user->is_deleted == 0)
            return response()->json(compact('token', 'userName', 'userId', 'company', 'branch_office', 'country', 'userRole', 'branch_name'));
        else
            return response()->json(['error' => 'El usuario no existe'], 404);
        //nombreusuario, idusuario, compañia, branch, roles
    }

    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }
}